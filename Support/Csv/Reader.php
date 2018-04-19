<?php

namespace Simple\Support\Csv;

use SplFileObject;

class Reader
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
     * Reader constructor.
     * @param $file
     */
    public function __construct($file)
    {
        if (file_exists($file)) {
            $this->file = $file;
        }
    }

    /**
     * 新建一个FileObject
     */
    public function openFile()
    {
        if ($this->fileObject == null) {
            $this->fileObject = new SplFileObject($this->file, 'rb');
        }
    }

    /**
     * 读取整个csv的行数
     *
     * @return int
     */
    public function getLine()
    {
        $this->openFile();
        $this->fileObject->seek(filesize($this->file));
        return $this->fileObject->key();
    }

    /**
     * 读取第一行数据
     *
     * @return array
     */
    public function getFirst()
    {
        return $this->getData(1, 1);
    }

    /**
     * 读取数据
     *
     * @param int $start 第一行从1开始
     * @param int $length 读取的行数 0=读取所有行
     * @return array
     */
    public function getData($start = 1, $length = 0)
    {
        $this->openFile();
        $length = $length ? $length : $this->getLine();
        $data = [];

        $flag = $start === 2 ? true : false;
        $start = $start - 2;
        $start = ($start < 0) ? 0 : $start;

        $length = $flag ? $length + 1 : $length;
        $this->fileObject->seek($start);

        while ($length-- && !$this->fileObject->eof()) {
            $data[] = $this->fileObject->fgetcsv();
            $this->fileObject->next();
        }

        if ($flag) {
            array_shift($data);
        }

        return $data;
    }
}
