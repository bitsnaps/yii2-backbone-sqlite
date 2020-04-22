<?php
namespace app\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * @link http://www.frenzel.net/
 * @author Philipp Frenzel <philipp@frenzel.net>
 */
class backboneAsset extends AssetBundle
{
    /**
     * [$sourcePath description]
     * @var string
     */
    // public $sourcePath = '@bower';
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    /**
     * [$js description]
     * @var array
     */
    public $js = [
        // 'underscore/underscore.js',
        // 'backbone/backbone.js'
        'js/underscore-min.js',
        'js/backbone-min.js'
    ];

    /**
     * [$depends description]
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
