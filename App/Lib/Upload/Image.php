<?php

namespace App\Lib\Upload;

use App\Lib\Upload\Base;

class Image extends Base
{

	/**
	 * fileType
	 * @var string
	 */
	public $fileType = "image";

	public $maxSize = 1024 * 100;

	/**
	 * 文件后缀的medaiTypw
	 * @var [type]
	 */
	public $fileExtTypes = [
		'png',
		'jpeg',
		'jpg',
		'gif'
	];
}
