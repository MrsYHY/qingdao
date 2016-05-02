<?php
/**
 * User: yoyo
 * Date: 15-9-13
 * Time: 下午9:51
 */

namespace backend\widgets\metronic;


use frontend\widgets\LinkPager;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;
use yii\helpers\Url;
use yii\widgets\LinkSorter;

class TableView extends Widget{

    /**
     * @var \yii\data\DataProviderInterface the data provider for the view. This property is required.
     */
    public $dataProvider;
    /**
     * @var array the configuration for the pager widget. By default, [[LinkPager]] will be
     * used to render the pager. You can use a different widget class by configuring the "class" element.
     * Note that the widget must support the `pagination` property which will be populated with the
     * [[\yii\data\BaseDataProvider::pagination|pagination]] value of the [[dataProvider]].
     */
    public $pager = [];
    /**
     * @var array the configuration for the sorter widget. By default, [[LinkSorter]] will be
     * used to render the sorter. You can use a different widget class by configuring the "class" element.
     * Note that the widget must support the `sort` property which will be populated with the
     * [[\yii\data\BaseDataProvider::sort|sort]] value of the [[dataProvider]].
     */
    public $sorter = [];
    /**
     * @var string the HTML content to be displayed as the summary of the list view.
     * If you do not want to show the summary, you may set it with an empty string.
     *
     * The following tokens will be replaced with the corresponding values:
     *
     * - `{begin}`: the starting row number (1-based) currently being displayed
     * - `{end}`: the ending row number (1-based) currently being displayed
     * - `{count}`: the number of rows currently being displayed
     * - `{totalCount}`: the total number of rows available
     * - `{page}`: the page number (1-based) current being displayed
     * - `{pageCount}`: the number of pages available
     */
    public $summary;
    /**
     * @var array the HTML attributes for the summary of the list view.
     * The "tag" element specifies the tag name of the summary element and defaults to "div".
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $summaryOptions = ['class' => 'summary'];
    /**
     * @var boolean whether to show the list view if [[dataProvider]] returns no data.
     */
    public $showOnEmpty = false;
    /**
     * @var string the HTML content to be displayed when [[dataProvider]] does not have any data.
     */
    public $emptyText;
    /**
     * @var array the HTML attributes for the emptyText of the list view.
     * The "tag" element specifies the tag name of the emptyText element and defaults to "div".
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $emptyTextOptions = ['class' => 'empty'];
    /**
     * @var string the layout that determines how different sections of the list view should be organized.
     * The following tokens will be replaced with the corresponding section contents:
     *
     * - `{summary}`: the summary section. See [[renderSummary()]].
     * - `{items}`: the list items. See [[renderItems()]].
     * - `{sorter}`: the sorter. See [[renderSorter()]].
     * - `{pager}`: the pager. See [[renderPager()]].
     */
    public $layout = "{filter}\n{items}\n{pager}";

    /**
     * @var array the HTML attributes for the container of the rendering result of each data model.
     * The "tag" element specifies the tag name of the container element and defaults to "div".
     * If "tag" is false, it means no container element will be rendered.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $itemOptions = [];
    /**
     * @var string|callable the name of the view for rendering each data item, or a callback (e.g. an anonymous function)
     * for rendering each data item. If it specifies a view name, the following variables will
     * be available in the view:
     *
     * - `$model`: mixed, the data model
     * - `$key`: mixed, the key value associated with the data item
     * - `$index`: integer, the zero-based index of the data item in the items array returned by [[dataProvider]].
     * - `$widget`: ListView, this widget instance
     *
     * Note that the view name is resolved into the view file by the current context of the [[view]] object.
     *
     * If this property is specified as a callback, it should have the following signature:
     *
     * ~~~
     * function ($model, $key, $index, $widget)
     * ~~~
     */
    public $itemView;
    /**
     * @var array additional parameters to be passed to [[itemView]] when it is being rendered.
     * This property is used only when [[itemView]] is a string representing a view name.
     */
    public $viewParams = [];
    /**
     * @var string the HTML code to be displayed between any two consecutive items.
     */
    public $separator = "\n";

    /**
     * @var array the HTML attributes for the container tag of the list view.
     * The "tag" element specifies the tag name of the container element and defaults to "div".
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = ['class' => 'dataTable'];

    public $filters =  [];

    public $filterAction;
    /**
     * @var string|array
     */
    public $headers;

    public $tableOption = ['class'=>'table table-striped table-bordered table-hover dataTable no-footer'];

    public $tableWrapOption = ['class'=>'table-scrollable table-responsive'];

    /**
     * Initializes the view.
     */
    public function init()
    {
        if ($this->dataProvider === null) {
            throw new InvalidConfigException('The "dataProvider" property must be set.');
        }
        if ($this->emptyText === null) {
            $this->emptyText = Yii::t('yii', 'No results found.');
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderSection($matches[0]);
            return $content === false ? $matches[0] : $content;
        }, $this->layout);
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        echo Html::tag($tag, $content, $options);
    }

    /**
     * Renders a section of the specified name.
     * If the named section is not supported, false will be returned.
     * @param string $name the section name, e.g., `{summary}`, `{items}`.
     * @return string|boolean the rendering result of the section, or false if the named section is not supported.
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{filter}':
                return $this->renderFilter();
            case '{items}':
                return $this->renderItems();
            case '{pager}':
                return $this->renderPager();
            case '{sorter}':
                return $this->renderSorter();
            default:
                return false;
        }
    }

    /**
     * Renders the data models.
     * @return string the rendering result.
     */
    public function renderItems(){

        $models = $this->dataProvider->getModels();
        $keys = $this->dataProvider->getKeys();
        $rows = [];
        if ($this->showOnEmpty || $this->dataProvider->getCount() > 0) {
            foreach (array_values($models) as $index => $model) {
                $rows[] = $this->renderItem($model, $keys[$index], $index);
            }
        } else {
            $rows[] = $this->renderEmpty();
        }
        $content =  implode($this->separator, $rows);
        $contents = '<tbody>'.$content.'</tbody>';
        $header = '';
        if(is_array($this->headers)){
            foreach($this->headers as $headerStr)
                $header.= '<th>'.$headerStr.'</th>';
        } else
            $header = $this->headers;
        $headers = '<thead><tr>'.$header.'</tr></thead>';
        return Html::tag('div',Html::tag('table',$headers.$contents,$this->tableOption),$this->tableWrapOption);
    }

    /**
     * Renders a single data model.
     * @param mixed $model the data model to be rendered
     * @param mixed $key the key value associated with the data model
     * @param integer $index the zero-based index of the data model in the model array returned by [[dataProvider]].
     * @return string the rendering result
     */
    public function renderItem($model, $key, $index)
    {
        if ($this->itemView === null) {
            $content = $key;
        } elseif (is_string($this->itemView)) {
            $content = $this->getView()->render($this->itemView, array_merge([
                'model' => $model,
                'key' => $key,
                'index' => $index,
                'widget' => $this,
            ], $this->viewParams));
        } else {
            $content = call_user_func($this->itemView, $model, $key, $index, $this);
        }
        return $content;
//        $options = $this->itemOptions;
//
//        $tag = ArrayHelper::remove($options, 'tag', 'div');
//        if ($tag !== false) {
//            $options['data-key'] = is_array($key) ? json_encode($key, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : (string) $key;
//
//            return Html::tag($tag, $content, $options);
//        } else {
//            return $content;
//        }
    }

    public function renderFilter()
    {
        if(is_array($this->filters)){
            $content = '';
            foreach($this->filters as $filter){
                $content.=$filter;
            }
        } else
            $content =  $this->filters;
        $content.='<input type="hidden" name="r" value="'.Yii::$app->controller->module->requestedRoute.'" /><button class="btn btn-sm blue table-group-action-submit" id="batSub" style="margin-left: 5px"><i class="icon-magnifier"></i> 查询</button>';
        $this->filterAction = $this->filterAction?$this->filterAction:Yii::$app->request->absoluteUrl;
        $content = Html::tag('form',$content,['class'=>'form-inline pull-right','action'=>Url::to($this->filterAction),'method'=>'get']);
        return Html::tag('div',$content,['class'=>'col-md-12','class'=>'filters']);

    }

    /**
     * Renders the HTML content indicating that the list view has no data.
     * @return string the rendering result
     * @see emptyText
     */
    public function renderEmpty()
    {
        $options = $this->emptyTextOptions;
        return Html::tag('tr', '<td colspan="'.count($this->headers).'">'.($this->emptyText === null ? Yii::t('yii', 'No results found.') : $this->emptyText).'</td>', $options);
    }

    /**
     * Renders the summary text.
     */
    public function renderSummary()
    {
        $count = $this->dataProvider->getCount();
        if ($count <= 0) {
            return '';
        }
        $summaryOptions = $this->summaryOptions;
        $tag = ArrayHelper::remove($summaryOptions, 'tag', 'div');
        if (($pagination = $this->dataProvider->getPagination()) !== false) {
            $totalCount = $this->dataProvider->getTotalCount();
            $begin = $pagination->getPage() * $pagination->pageSize + 1;
            $end = $begin + $count - 1;
            if ($begin > $end) {
                $begin = $end;
            }
            $page = $pagination->getPage() + 1;
            $pageCount = $pagination->pageCount;
            if (($summaryContent = $this->summary) === null) {
                return Html::tag($tag, Yii::t('yii', 'Showing <b>{begin, number}-{end, number}</b> of <b>{totalCount, number}</b> {totalCount, plural, one{item} other{items}}.', [
                    'begin' => $begin,
                    'end' => $end,
                    'count' => $count,
                    'totalCount' => $totalCount,
                    'page' => $page,
                    'pageCount' => $pageCount,
                ]), $summaryOptions);
            }
        } else {
            $begin = $page = $pageCount = 1;
            $end = $totalCount = $count;
            if (($summaryContent = $this->summary) === null) {
                return Html::tag($tag, Yii::t('yii', 'Total <b>{count, number}</b> {count, plural, one{item} other{items}}.', [
                    'begin' => $begin,
                    'end' => $end,
                    'count' => $count,
                    'totalCount' => $totalCount,
                    'page' => $page,
                    'pageCount' => $pageCount,
                ]), $summaryOptions);
            }
        }

        return Yii::$app->getI18n()->format($summaryContent, [
            'begin' => $begin,
            'end' => $end,
            'count' => $count,
            'totalCount' => $totalCount,
            'page' => $page,
            'pageCount' => $pageCount,
        ], Yii::$app->language);
    }

    /**
     * Renders the pager.
     * @return string the rendering result
     */
    public function renderPager()
    {
        $pagination = $this->dataProvider->getPagination();
        if ($pagination === false || $this->dataProvider->getCount() <= 0) {
            return '';
        }
        /* @var $class LinkPager */
        $pager = $this->pager;
        $class = ArrayHelper::remove($pager, 'class', \yii\widgets\LinkPager::className());
        $pager['pagination'] = $pagination;
        $pager['view'] = $this->getView();

        $pagerContent = Html::tag('div',$class::widget($pager));
        $summer = $this->renderSummary();
        return Html::tag('div',$summer.$pagerContent,['class'=>'gird-view-footer']);
    }

    /**
     * Renders the sorter.
     * @return string the rendering result
     */
    public function renderSorter()
    {
        $sort = $this->dataProvider->getSort();
        if ($sort === false || empty($sort->attributes) || $this->dataProvider->getCount() <= 0) {
            return '';
        }
        /* @var $class LinkSorter */
        $sorter = $this->sorter;
        $class = ArrayHelper::remove($sorter, 'class', LinkSorter::className());
        $sorter['sort'] = $sort;
        $sorter['view'] = $this->getView();

        return $class::widget($sorter);
    }
} 