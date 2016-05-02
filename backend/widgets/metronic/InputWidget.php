<?php

namespace backend\widgets\metronic;

use yii\base\Model;
use yii\bootstrap\Widget;
use yii\helpers\Html;
use Yii;

/**
 * Class InputWidget is the base class for all Metronic input widgets.
 */
class InputWidget extends Widget
{
	/**
	 * @var Model the data model that this widget is associated with.
	 */
	public $model;
	/**
	 * @var string the model attribute that this widget is associated with.
	 */
	public $attribute;
	/**
	 * @var string the input name. This must be set if [[model]] and [[attribute]] are not set.
	 */
	public $name;
	/**
	 * @var string the input value.
	 */
	public $value;
	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		if (!$this->hasModel() && $this->name === null) {
			$this->name = get_class($this);
		}
		if (!isset($this->options['id'])) {
			$this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
		}
		parent::init();
	}

	/**
	 * @return boolean whether this widget is associated with a data model.
	 */
	protected function hasModel()
	{
		return $this->model instanceof Model && $this->attribute !== null;
	}
}
