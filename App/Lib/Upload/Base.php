<?php

namespace App\Lib\Upload;

use App\Lib\Utils;

class Base
{

	/**
	 * 上传文件的 file - key
	 * @var string
	 */
	public $type = "";

	public function __construct($request, $type = null)
	{
		$this->request = $request;

		if (empty($type)) {
			$this->type = array_keys($this->request->getSwooleRequest()->files)[0];
		} else {
			$this->type = $type;
		}
	}

	public function upload()
	{
		if ($this->type != $this->fileType) {
			throw new \Exception("上传文件格式错误");
		}

		$file = $this->request->getUploadedFile($this->type);
		$this->checkFile($file);

		$this->size = $file->getSize();
		$this->checkSize($this->size);

		$this->clientMediaType = $file->getClientMediaType();
		$this->checkMediaType($this->clientMediaType);

		$fileName = $file->getClientFileName();
		$files = $this->getFile($fileName);

		$moveFile = $file->moveTo($files);

		if (!empty($moveFile)) {
			return $this->file;
		}

		return false;
	}

	public function getFile($fileName)
	{
		$pathinfo = pathinfo($fileName);

		$extension = $pathinfo['extension'];

		$dirname = "/" . $this->type . "/" . date("Y") . "/" . date("m");

		$dir = EASYSWOOLE_ROOT  . "/public" . $dirname;

		if (!is_dir($dir)) {
			@mkdir($dir, 0777, true);
		}

		$basename = "/" . Utils::getFileKey($fileName) . "." . $extension;

		$this->file = $dirname . $basename;

		return $dir  . $basename;
	}

	public function checkMediaType($clientMediaType)
	{
		$clientMediaType = explode("/", $clientMediaType);

		$clientMediaType = $clientMediaType[1] ?? "";

		if (empty($clientMediaType)) {
			throw new \Exception("没有上传{$this->type}文件");
		}

		if (!in_array($clientMediaType, $this->fileExtTypes)) {
			throw new \Exception("上传{$this->type}文件不合法");
		}

		return true;
	}

	public function checkSize($size)
	{
		if (empty($size)) {
			throw new \Exception("文件超过2M，请重新选择");
		}
	}

	public function checkFile($file)
	{
		if (empty($file)) {
			throw new \Exception("没有上传文件，请选择文件");
		}
	}
}
