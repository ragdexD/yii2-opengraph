<?php

namespace ragdexD\opengraph;

use Yii;
use yii\web\View;

/**
 * Description of OpenGraph
 *
 * @author ed
 */
class OpenGraph {

    public $title;
    public $site_name;
    public $description;
    public $locale;
    public $url;
    public $type;
    public $image;
    public $app_id;
    
    

    function __construct() {
        //Default 
        $this->title = Yii::$app->name;
        $this->site_name = Yii::$app->name;
        $this->url = Yii::$app->request->absoluteUrl;
        $this->description = null;
        $this->type = 'website'; //article
        $this->locale = str_replace('-', '_', Yii::$app->language);
        $this->image = null;

        // Twitter Card
        $this->twitter = new TwitterCard;

        // Listed to Begin Page View event to start adding meta
        Yii::$app->view->on(View::EVENT_BEGIN_PAGE, function() {
            // Register required and easily determined open graph data
            Yii::$app->controller->view->registerMetaTag(['property' => 'og:title', 'content' => $this->title], 'og:title');
            Yii::$app->controller->view->registerMetaTag(['property' => 'og:site_name', 'content' => $this->site_name], 'og:site_name');
            Yii::$app->controller->view->registerMetaTag(['property' => 'og:url', 'content' => $this->url], 'og:url');
            Yii::$app->controller->view->registerMetaTag(['property' => 'og:type', 'content' => $this->type], 'og:type');

            // Locale issafe to be specifued since it has default value on Yii applications
            Yii::$app->controller->view->registerMetaTag(['property' => 'og:locale', 'content' => $this->locale], 'og:locale');

            // Only add a description meta if specified
            if ($this->description !== null) {
                Yii::$app->controller->view->registerMetaTag(['property' => 'og:description', 'content' => $this->description], 'og:description');
            }

            // Only add an image meta if specified
            if ($this->image !== null) {
                Yii::$app->controller->view->registerMetaTag(['property' => 'og:image', 'content' => $this->image], 'og:image');
            }
            // Only add an app_id meta if specified
            if ($this->app_id !== null) {
                Yii::$app->controller->view->registerMetaTag(['property' => 'fb:app_id', 'content' => $this->app_id], 'fb:app_id');
            }

            $this->twitter->registerTags();
        });
    }

    public function set($metas = []) {
        // Massive assignment by array
        foreach ($metas as $property => $content) {
            if ($property == 'twitter') {
                $this->twitter->set($content);
            } else if (property_exists($this, $property)) {
                $this->$property = $content;
            }
        }
    }

}
