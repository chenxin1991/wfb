<aside class="main-sidebar">

    <section class="sidebar">

    <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    [
                        'label' => '平台基础数据',
                        'icon' => 'user',
                        'url' => '#',
                        'items' => [
                            ['label' => '平台管理', 'icon' => 'circle-o', 'url' => ['/platform'],],
                            ['label' => '行业管理', 'icon' => 'circle-o', 'url' => ['/industry'],],
                            ['label' => '产品管理', 'icon' => 'circle-o', 'url' => ['/product'],],  
                            ['label' => '站点管理', 'icon' => 'circle-o', 'url' => ['/website'],],
                            ['label' => '模板管理', 'icon' => 'circle-o', 'url' => ['/template'],],
                        ],
                    ],
                    [
                        'label' => '素材库',
                        'icon' => 'file-text',
                        'url' => '#',
                        'items' => [
                            ['label' => '长尾关键词管理', 'icon' => 'circle-o', 'url' => ['/longtail-keywords'],],
                            ['label' => '主关键词管理', 'icon' => 'circle-o', 'url' => ['/main-keywords'],],
                            ['label' => '私有图片管理', 'icon' => 'circle-o', 'url' => ['/private-image'],],
                            ['label' => '词头词尾管理', 'icon' => 'circle-o', 'url' => ['/word'],],
                            ['label' => '首段管理', 'icon' => 'circle-o', 'url' => ['/first-paragraph'],],
                            ['label' => '尾段管理', 'icon' => 'circle-o', 'url' => ['/end-paragraph'],], 
                            ['label' => '段落管理', 'icon' => 'circle-o', 'url' => ['/paragraph'],],
                            ['label' => '公有图片管理', 'icon' => 'circle-o', 'url' => ['/public-image'],],
                        ],
                    ],
                    [
                        'label' => '文章发布',
                        'icon' => 'cloud-upload',
                        'url' => '#',
                        'items' => [
                            ['label' => '文章发布管理', 'icon' => 'circle-o', 'url' => ['/article-mix'],],
                            ['label' => '文章草稿箱', 'icon' => 'circle-o', 'url' => ['/article'],],
                        ],
                    ],
                    [
                        'label' => '站内站发布',
                        'icon' => 'cloud-upload',
                        'url' => '#',
                        'items' => [
                            ['label' => '站内站发布管理', 'icon' => 'circle-o', 'url' => ['/station-mix'],],
                            ['label' => '站内站草稿箱', 'icon' => 'circle-o', 'url' => ['/station'],],
                        ],
                    ],
                    [
                        'label' => '系统管理',
                        'icon' => 'cog',
                        'url' => '#',
                        'items' => [
                            ['label' => '用户管理', 'icon' => 'circle-o', 'url' => ['/user'],],
                            ['label' => '系统设置', 'icon' => 'circle-o', 'url' => ['/config'],],
                        ],
                    ],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    // [
                    //     'label' => '开发工具',
                    //     'icon' => 'share',
                    //     'url' => '#',
                    //     'items' => [
                    //         ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                    //         ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                    //         [
                    //             'label' => 'Level One',
                    //             'icon' => 'circle-o',
                    //             'url' => '#',
                    //             'items' => [
                    //                 ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                    //                 [
                    //                     'label' => 'Level Two',
                    //                     'icon' => 'circle-o',
                    //                     'url' => '#',
                    //                     'items' => [
                    //                         ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                    //                         ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                    //                     ],
                    //                 ],
                    //             ],
                    //         ],
                    //     ],
                    // ],
                ],
            ]
        ) ?>
    </section>
</aside>
