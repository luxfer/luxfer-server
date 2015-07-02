<?php

namespace App\Presenters;

use Nette;
use	App\Model;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	protected $cellsRepository;
	protected $configRepository;
	protected $config;
	
	protected function startup()
	{		
		parent::startup();
		$this->configRepository = $this->context->configRepository;		
		$this->cellsRepository = $this->context->cellsRepository;

		// get config
		$res = $this->configRepository->findAll();
		$this->config = array();
		foreach($res as $r)
			$this->config[$r->name] = $r->value;
		$this->template->config = $this->config;
	}
}
