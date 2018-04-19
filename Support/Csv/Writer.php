<?php

namespace Simple\Support\Csv;

use SplFileObject;

class Writer
{
    /**
     * @var string
     */
    protected $file = '';

    /**
     * @var null | SplFileObject
     */
    protected $fileObject = null;

    /**
     * Writer constructor.
     * @param string $file 文件名称
     * @param array $data 写入的数据
     * @param bool $utf8 文件中是否写入UTF8 BOM头
     */
    public function __construct($file, $data, $utf8 = true)
    {
        $this->file = $file;
        $this->openFile();

        // 写入UTF-8 BOM头
        if ($utf8) {
            file_put_contents($this->file, chr(0xEF).chr(0xBB).chr(0xBF));
        }

        foreach ($data as $value) {
            $this->fileObject->fputcsv($value);
        }
    }

    /**
     * 新建一个FileObject
     */
    public function openFile()
    {
        if ($this->fileObject == null) {
            $this->fileObject = new SplFileObject($this->file, 'w');
        }
    }
}
