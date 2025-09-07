<?php

namespace App\Lib;

use Exception;
use \PhpOffice\PhpSpreadsheet\IOFactory;

class ImportFileReader
{
    public $dataInsertMode = true;
    public $columns = [];
    public $uniqueColumns = [];
    public $modelName;
    public $file;
    public $fileSupportedExtension = ['csv', 'xlsx'];
    public $allData = [];
    public $allUniqueData = [];
    public $notify = [];
    public $skippedRows = [];

    public function __construct($file, $modelName = null)
    {
        $this->file      = $file;
        $this->modelName = $modelName;
    }

    public function readFile()
    {
        $fileExtension = strtolower($this->file->getClientOriginalExtension());
        if (!in_array($fileExtension, $this->fileSupportedExtension)) {
            return $this->exceptionSet("File type not supported. Supported: " . implode(', ', $this->fileSupportedExtension));
        }
        try {
            $spreadsheet = IOFactory::load($this->file);
            $data = $spreadsheet->getActiveSheet()->toArray();
        } catch (\Exception $ex) {
            return $this->exceptionSet("Failed to read file: " . $ex->getMessage());
        }
        if (!is_array($data) || count($data) <= 0) {
            return $this->exceptionSet("File cannot be empty or unreadable");
        }
        $header = $data[0];
        if (count($header) < count($this->columns)) {
            return $this->exceptionSet("File columns do not match required template");
        }
        $this->validateFileHeader(array_filter(@$data[0]));
        unset($data[0]);
        foreach ($data as $rowIndex => $item) {
            $item = array_map('trim', $item);
            // Language/Country flag support: If columns include 'language' or 'country', validate here
            if (in_array('language', $this->columns) && empty($item[array_search('language', $this->columns)])) {
                $this->skippedRows[] = ['row' => $rowIndex + 2, 'reason' => 'Missing language'];
                continue;
            }
            if (in_array('country', $this->columns) && empty($item[array_search('country', $this->columns)])) {
                $this->skippedRows[] = ['row' => $rowIndex + 2, 'reason' => 'Missing country'];
                continue;
            }
            if ($this->uniqueColumCheck($item)) {
                $this->skippedRows[] = ['row' => $rowIndex + 2, 'reason' => 'Duplicate row'];
                continue;
            }
            $this->dataReadFromFile($item);
        }
        return $this->saveData();
    }

    public function validateFileHeader($fileHeader)
    {
        if (!is_array($fileHeader) || count($fileHeader) != count($this->columns)) {
            $this->exceptionSet("Invalid file format");
        }

        foreach ($fileHeader as $k => $header) {
            if (trim(strtolower($header)) != strtolower(@$this->columns[$k])) {
                $this->exceptionSet("Invalid file format");
            }
        }
    }

    public function dataReadFromFile($data)
    {

        if (gettype($data) != 'array') {
            return $this->exceptionSet('Invalid data formate provided inside upload file.');
        }

        $this->allUniqueData[] = array_combine($this->columns, $data);

        $this->allData[] = $data;
    }

    function uniqueColumCheck($data)
    {

        $combinedData      = array_combine($this->columns, $data);
        $uniqueColumns     = array_intersect($this->uniqueColumns, $this->columns);
        foreach ($uniqueColumns as $uniqueColumn) {
            $uniqueColumnsValue = $combinedData[$uniqueColumn];
            if ($uniqueColumnsValue && $uniqueColumn) {
                if ($this->modelName::where($uniqueColumn, $uniqueColumnsValue)->exists()) {
                    return true; // Duplicate found
                }
            }
        }
        return false; // No duplicate
    }

    public function saveData()
    {
        if (count($this->allUniqueData) > 0 && $this->dataInsertMode) {
            try {
                \DB::beginTransaction();
                $batchSize = 1000;
                $chunks = array_chunk($this->allUniqueData, $batchSize);
                foreach ($chunks as $chunk) {
                    $this->modelName::insert($chunk);
                }
                \DB::commit();
                $skippedCount = count($this->skippedRows);
                $message = "Data uploaded successfully.";
                if ($skippedCount > 0) {
                    $message .= " {$skippedCount} row(s) were skipped (duplicates, missing language/country, etc).";
                }
                $this->notify = [
                    'success' => true,
                    'message' => $message,
                    'skippedRows' => $this->skippedRows
                ];
            } catch (Exception $e) {
                \DB::rollBack();
                $this->exceptionSet('Upload failed: ' . $e->getMessage());
            }
        } else {
            $this->notify = [
                'success' => false,
                'message' => "No valid data to upload.",
                'skippedRows' => $this->skippedRows
            ];
        }
    }

    public function exceptionSet($exception)
    {
        throw new Exception($exception);
    }

    public function getReadData()
    {
        return $this->allData;
    }

    public function notifyMessage()
    {
        $notify = (object) $this->notify;
        return $notify;
    }
}
