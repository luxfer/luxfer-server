<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Tracy\Debugger;
use stdClass;

class HomepagePresenter extends BasePresenter
{
	protected $colors;
	
	protected function startup()
	{		
		parent::startup();
		$this->colors = array('F80000', 'FF4500', '00FF00', '99FF00', 'FFFF00', 'FF00FF', '66FFFF', '3300FF', '0000FF', 'FFFFCC', '660099', 'FFC0CB', '000000');
	}
	
	public function renderDefault()
	{
	}

	// determine new state of cell
	protected function cellState($state, $sum, $neighbours)
	{
		if(($state == 0 && $sum == 3) || ($state > 0 && ($sum == 2 || $sum == 3)))
		{
			$hist = array_count_values($neighbours);
			if(count($hist) > 1) // neighbours have different types
				return mt_rand(1,13);
			else
				return array_keys($hist)[0]; // all neighbours are same or dead
		}
		else
			return 0;
	}

	// one step of life
	protected function life(&$cells, $cellsX, $cellsY)
	{
		$newCells = array_pad(array(), $cellsX * $cellsY, 0);
		for($x = 0; $x < $cellsX; $x ++)
		{
			for($y = 0; $y < $cellsY; $y ++)
			{
				$neighbours = array($cells[$y * $cellsX + ($x + 1) % $cellsX],
					$cells[$y * $cellsX + ($x - 1 + $cellsX) % $cellsX],
					$cells[(($y + 1) % $cellsY) * $cellsX + $x],
					$cells[(($y - 1 + $cellsY) % $cellsY) * $cellsX + $x],
					$cells[(($y - 1 + $cellsY) % $cellsY) * $cellsX + ($x + 1) % $cellsX],
					$cells[(($y - 1 + $cellsY) % $cellsY) * $cellsX + ($x - 1 + $cellsX) % $cellsX],
					$cells[(($y + 1) % $cellsY) * $cellsX + ($x + 1) % $cellsX],
					$cells[(($y + 1) % $cellsY) * $cellsX + ($x - 1 + $cellsX) % $cellsX]
				);
	
				$neighbours = array_filter($neighbours, function($d){ return $d != 0; });
				$sum = count($neighbours); 
				$newCells[$y * $cellsX + $x] = self::cellState($cells[$y * $cellsX + $x ], $sum, $neighbours);  
			}
		}
		$cells = $newCells;
	}
	
	// process one animation step
	protected function animation($cells, $cellsX, $cellsY)
	{
		mt_srand(microtime(true));
		$cellsCount = $cellsX * $cellsY;
 
		// set cells
		self::life($cells, $cellsX, $cellsY);
		for($i = 0; $i < $cellsCount; $i ++)
			$cells[$i] = array('id' => $i, 'state' => $cells[$i]);
		$this->cellsRepository->insertUpdate($cells);

		// set timer
		$this->configRepository->updateBy(array('name' => 'last_update'), array('value' => microtime(true)));
	}

	// return current state
	public function actionGetCells($slice = null)
	{
		$cells = array();
		$cellsCount = $this->config['cells_x'] * $this->config['cells_y'];
		$cells = array_map(function($d){ return $d->state; }, $this->cellsRepository->findCells($cellsCount)->fetchAll());

		// adnimation
		if((float)$this->config['last_update'] < microtime(true) - $this->config['animation_step'])
			self::animation($cells, $this->config['cells_x'], $this->config['cells_y']);
		
		// create return object
		$return = array();

		// output for wall		
		if($slice)
		{
			// selection coordinates
			$sX = (int)($this->config['selection_x']);
			$sY = (int)($this->config['selection_y']);
			$newCells = array();
			$id = 0;
			$cellsX = $this->config['cells_x']; 
			foreach($cells as $c)
			{
				$x = $id % $cellsX;
				$y = (int)($id / $cellsX);
				if($sX <= $x && $sX + 5 > $x && $sY <= $y && $sY + 10 > $y)
				{
					$color = $c > 0 ? $this->colors[$c - 1] : $this->config['background'];
					$newCells[] = '#' . ($color == '222222' ? '000000' : $color);
				}
				$id ++;
			}
			$return['cells'] = $newCells;
		}
		// output for website
		else
		{
			// selection
			$return['selection'] = array('x' => $this->config['selection_x'], 'y' => $this->config['selection_y']);
			
			// cells
			$return['cells'] = $cells;
		
			// background color - dead cells color
			$return['background'] = $this->config['background'];
		}
		$this->sendResponse(new Nette\Application\Responses\JsonResponse($return));			
	}
	
	// update BG color
	public function actionChangeColor($background)
	{
		if($this->isAjax() && strlen($background) == 6 && ctype_xdigit($background))
		{
			$this->configRepository->updateBy(array('name' => 'background'), array('value' => $background));
		}
		$this->sendResponse(new Nette\Application\Responses\JsonResponse(array()));			
	}
	
	// update position of selection
	public function actionSetSelection($x, $y)
	{
		$this->configRepository->updateBy(array('name' => 'selection_x'), array('value' => $x));
		$this->configRepository->updateBy(array('name' => 'selection_y'), array('value' => $y));
		$this->sendResponse(new Nette\Application\Responses\JsonResponse(array()));			
	}

	public function actionSetCells($cells, $selection = null)
	{
		if($selection)
		{
			$selection = json_decode($selection);
			$this->configRepository->updateBy(array('name' => 'selection_x'), array('value' => $selection->x));
			$this->configRepository->updateBy(array('name' => 'selection_y'), array('value' => $selection->y));
		}

		// update all cells
		$cells = array_map(function($d){ return array('id' => $d->id, 'state' => $d->state); }, json_decode($cells));
		$this->cellsRepository->insertUpdate($cells);
		$this->sendResponse(new Nette\Application\Responses\JsonResponse(array()));			
	}
}
