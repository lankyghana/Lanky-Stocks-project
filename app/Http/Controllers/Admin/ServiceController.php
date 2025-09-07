<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Models\ApiProvider;
use App\Models\Category;
use App\Models\Service;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ServiceController extends Controller {
    public function index() {
        $pageTitle  = "Manage Service";
        $categories = Category::active()->orderBy('name')->get();
        $apiLists   = ApiProvider::active()->get();
        $services   = Service::with('category', 'provider')
            ->searchable(['name'])
            ->filter(['category_id', 'api_provider_id', 'dripfeed'])
            ->dateFilter()
            ->whereHas('category', function ($query) {
                $query->active();
            })
            ->orderBy('name')
            ->paginate(getPaginate());

        return view('admin.service.index', compact('pageTitle', 'categories', 'services', 'apiLists'));
    }

    public function add() {
        $pageTitle  = "Add Service";
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.service.add', compact('pageTitle', 'categories'));
    }

    public function store(Request $request) {
        $id = $request->id ?? 0;
        $request->validate([
            'category'       => 'required',
            'name'           => 'required',
            'price_per_k'    => 'required|numeric|gt:0',
            'min'            => 'required|integer|gt:0|lte:' . $request->max,
            'max'            => 'required|integer|gte:' . $request->min,
            'details'        => 'required',
            'api_service_id' => 'nullable|integer|gt:0|unique:services,api_service_id,' . $id,
        ]);

        $category = Category::active()->findOrFail($request->category);

        if ($id) {
            $service = Service::findOrFail($id);
            $message = "Service updated successfully";
        } else {
            $service = new Service();
            $message = "Service added successfully";
        }

        $service->category_id     = $category->id;
        $service->api_provider_id = $request->api_provider_id ?? 0;
        $service->name            = $request->name;
        $service->price_per_k     = $request->price_per_k;
        $service->original_price  = $request->original_price ?? $request->price_per_k;
        $service->min             = $request->min;
        $service->max             = $request->max;
        $service->details         = $request->details;
        $service->api_service_id  = $request->api_service_id;
        $service->dripfeed        = $request->dripfeed ? Status::YES : Status::NO;
        $service->refill          = $request->refill ? Status::YES : Status::NO;
        $service->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function edit($id) {
        $pageTitle  = "Update Service";
        $service    = Service::findOrFail($id);
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.service.edit', compact('pageTitle', 'categories', 'service'));
    }

    public function status($id) {
        return Service::changeStatus($id);
    }

    public function delete($id) {
        $service = Service::findOrFail($id);
        
        // Check if service has any orders
        $orderCount = $service->orders()->count();
        if ($orderCount > 0) {
            $notify[] = ['error', 'Cannot delete service that has existing orders. Total orders: ' . $orderCount];
            return back()->withNotify($notify);
        }
        
        // Check if service has any user services
        $userServiceCount = $service->userServices()->count();
        if ($userServiceCount > 0) {
            $notify[] = ['error', 'Cannot delete service that has user services assigned. Total user services: ' . $userServiceCount];
            return back()->withNotify($notify);
        }
        
        // Check if service has any favorites
        $favoriteCount = $service->favorite()->count();
        if ($favoriteCount > 0) {
            // Remove favorites first
            $service->favorite()->delete();
        }
        
        $service->delete();
        
        $notify[] = ['success', 'Service deleted successfully'];
        return back()->withNotify($notify);
    }

    public function apiServicesStore(Request $request) {
        $request->validate([
            'category'        => 'required',
            'name'            => 'required',
            'original_price'  => 'required|numeric|gte:0',
            'price_per_k'     => 'required|numeric|gt:0',
            'min'             => 'required|integer|gt:0|lte:' . $request->max,
            'max'             => 'required|integer|gte:' . $request->min,
            'details'         => 'required',
            'api_provider_id' => 'numeric|gt:0',
            'dripfeed'        => 'required|in:0,1',
            'refill'          => 'required|in:0,1',
        ]);

        $category = Category::where('name', $request->category)->first();

        if (!$category) {
            $category       = new Category();
            $category->name = $request->category;
            $category->save();
        }

        $apiService = Service::where('api_service_id', $request->api_service_id)->where('api_provider_id', $request->api_provider_id)->first();

        if ($apiService) {
            $notify[] = ['error', "This API service already listed"];
            return back()->withNotify($notify);
        }

        $service                  = new Service();
        $service->category_id     = $category->id;
        $service->api_provider_id = $request->api_provider_id;
        $service->name            = $request->name;
        $service->original_price  = $request->original_price;
        $service->price_per_k     = $request->price_per_k;
        $service->min             = $request->min;
        $service->max             = $request->max;
        $service->details         = $request->details;
        $service->api_service_id  = $request->api_service_id;
        $service->dripfeed        = $request->dripfeed;
        $service->refill          = $request->refill;
        $service->save();

        $notify[] = ['success', 'Service added form API successfully'];
        return back()->withNotify($notify);
    }

    public function apiServices($id) {
        $pageTitle      = "API Services";
        $api            = ApiProvider::active()->findOrFail($id);
        $existsServices = Service::where('api_provider_id', $api->id)->count();
        $url            = $api->api_url;

        $arr = [
            'key'    => $api->api_key,
            'action' => 'services',
        ];
        $response = CurlRequest::curlPostContent($url, $arr);
        $response = json_decode($response);

        if (@$response->error) {
            $notify[] = ['info', 'Please enter your api credentials from API Setting Option'];
            $notify[] = ['error', $response->error];
            return back()->withNotify($notify);
        }
        $data = [];

        if (!is_null(@$response->data)) {
            $response = $response->data;
        }

        foreach (@$response as $value) {
            $value->api_id = $id;
            array_push($data, $value);
        }

        $response = collect($data);
        $response = $response->skip($existsServices);
        $services = $this->paginate($response, getPaginate(100), null, ['path' => route('admin.service.api', $id)]);
        return view('admin.service.api_services', compact('pageTitle', 'services'));
    }

    public function addService(Request $request) {
        if ($request['services'][0]['increaseTimes'] <= 0) {
            return response()->json(['error' => true, 'message' => 'Increase price must be greater than 0.']);
        }
        $services = $request->services;
        foreach ($services as $serviceValue) {
            $api_service_id  = $serviceValue['api_service_id'];
            $category        = $serviceValue['category'];
            $api_provider_id = $serviceValue['api_provider_id'];
            $checkService    = Service::where('api_service_id', $api_service_id)->where('api_provider_id', $api_provider_id)->first();

            if ($checkService) {
                continue;
            }
            $existCategory = Category::where('name', $category)->first();
            if ($existCategory) {
                $category_id = $existCategory->id;
            }

            if (!$existCategory) {
                $categoryNew       = new Category();
                $categoryNew->name = $category;
                $categoryNew->save();
                $category_id = $categoryNew->id;
            }

            $service                  = new Service();
            $service->category_id     = $category_id;
            $service->api_provider_id = $api_provider_id;
            $service->name            = $serviceValue['name'];
            $service->original_price  = $serviceValue['price_per_k'];
            $service->price_per_k     = $serviceValue['price_per_k'] * $serviceValue['increaseTimes'];
            $service->min             = $serviceValue['min'];
            $service->max             = $serviceValue['max'];
            $service->api_service_id  = $serviceValue['api_service_id'];
            $service->save();
        }

        return response()->json(['success' => true, 'message' => 'All service added successfully']);
    }

    public function paginate($items, $perPage = 15, $page = null, $options = []) {
        $page  = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function bulkAction(Request $request) {
        $request->validate([
            'ids'  => 'required',
            'type' => 'required|in:enable,disable,price,delete',
        ]);

        $ids = explode(",", $request->ids);
        if ($request->type == 'enable') {
            Service::whereIn('id', $ids)->update(['status' => Status::ENABLE]);
            $notify[] = ['success', 'Services enabled successfully'];
        }
        if ($request->type == 'disable') {
            Service::whereIn('id', $ids)->update(['status' => Status::DISABLE]);
            $notify[] = ['success', 'Services disabled successfully'];
        }
        if ($request->type == 'price') {
            $request->validate([
                'price_increase' => 'required|numeric|gt:0',
            ]);
            foreach ($ids as $id) {
                $service = Service::find($id);
                if (!$service) {
                    continue;
                }
                $priceIncrease = $service->price_per_k * $request->price_increase / 100;
                $service->price_per_k += $priceIncrease;
                $service->save();
            }
            $notify[] = ['success', 'Services price updated successfully'];
        }
        if ($request->type == 'delete') {
            $deletedCount = 0;
            $skippedCount = 0;
            
            foreach ($ids as $id) {
                $service = Service::find($id);
                if (!$service) {
                    continue;
                }
                
                // Check if service has any orders or user services
                $orderCount = $service->orders()->count();
                $userServiceCount = $service->userServices()->count();
                
                if ($orderCount > 0 || $userServiceCount > 0) {
                    $skippedCount++;
                    continue;
                }
                
                // Remove favorites first
                $service->favorite()->delete();
                $service->delete();
                $deletedCount++;
            }
            
            $message = "Deleted {$deletedCount} services successfully";
            if ($skippedCount > 0) {
                $message .= ". Skipped {$skippedCount} services that have existing orders or user services.";
            }
            $notify[] = ['success', $message];
        }
        return back()->withNotify($notify);
    }

    public function import(Request $request) {
        try {
            $columns  = ['category_id', 'name', 'price_per_k', 'original_price', 'min', 'max', 'details', 'api_service_id', 'api_provider_id', 'dripfeed', 'refill'];
            $import   = importFileReader($request->file, $columns);
            $notify[] = ['success', @$import->notify['message']];
            return back()->withNotify($notify);
        } catch (Exception $ex) {
            $notify[] = ['error', $ex->getMessage()];
            return back()->withNotify($notify);
        }
    }
    
    public function syncPrices() {
        $pageTitle = "Sync Provider Prices";
        
        // Get all API providers
        $apiProviders = ApiProvider::active()->get();
        $syncResults = [];
        
        foreach ($apiProviders as $provider) {
            $result = $this->syncProviderPrices($provider);
            $syncResults[$provider->name] = $result;
        }
        
        return view('admin.service.sync_prices', compact('pageTitle', 'syncResults'));
    }
    
    public function syncProviderPrices(ApiProvider $provider) {
        try {
            $url = $provider->api_url;
            $arr = [
                'key' => $provider->api_key,
                'action' => 'services',
            ];
            
            $response = CurlRequest::curlPostContent($url, $arr);
            $response = json_decode($response);
            
            if (@$response->error) {
                return [
                    'success' => false,
                    'message' => $response->error,
                    'updated' => 0
                ];
            }
            
            $data = !is_null(@$response->data) ? $response->data : $response;
            if (!$data) {
                return [
                    'success' => false,
                    'message' => 'No data received from API',
                    'updated' => 0
                ];
            }
            
            $updatedCount = 0;
            
            foreach ($data as $apiService) {
                if (!isset($apiService->service) || !isset($apiService->rate)) {
                    continue;
                }
                
                $service = Service::where('api_service_id', $apiService->service)
                                ->where('api_provider_id', $provider->id)
                                ->first();
                
                if ($service) {
                    $newPrice = floatval($apiService->rate);
                    
                    if ($service->original_price != $newPrice) {
                        // Calculate new price_per_k based on the same multiplier
                        $multiplier = $service->original_price > 0 ? ($service->price_per_k / $service->original_price) : 1;
                        
                        $service->original_price = $newPrice;
                        $service->price_per_k = $newPrice * $multiplier;
                        $service->save();
                        
                        $updatedCount++;
                    }
                }
            }
            
            return [
                'success' => true,
                'message' => "Successfully synced prices",
                'updated' => $updatedCount
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'updated' => 0
            ];
        }
    }
    
    public function syncSingleProvider(Request $request, $providerId) {
        $provider = ApiProvider::active()->findOrFail($providerId);
        $result = $this->syncProviderPrices($provider);
        
        if ($result['success']) {
            $notify[] = ['success', $result['message'] . '. Updated ' . $result['updated'] . ' services.'];
        } else {
            $notify[] = ['error', $result['message']];
        }
        
        return back()->withNotify($notify);
    }
}
