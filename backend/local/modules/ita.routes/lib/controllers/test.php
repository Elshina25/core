<?php

namespace Ita\Routes\Controllers;

class Test extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return [
			'hello' => [
				'prefilters' => [],
				'postfilters' => []
			]
		];
	}

	public function helloAction()
    {
       	return [
			"Hello!"
		];
    }
}
