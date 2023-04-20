<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container" id="vendor-page">
    <h1 class="vendor-title"><?= lang('vendor_view') . $vendorInfo['name'] ?></h1>
    <div class="row">
        <div class="col-md-3">
            <div class="filter-sidebar">
                <div class="title">
                    <span><?= lang('categories') ?></span>
                    <?php if (isset($_GET['category']) && $_GET['category'] != '') { ?>
                        <a href="javascript:void(0);" class="clear-filter" data-type-clear="category" data-toggle="tooltip" data-placement="right" title="<?= lang('clear_the_filter') ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                    <?php } ?>
                </div>
                <a href="javascript:void(0)" id="show-xs-nav" class="visible-xs visible-sm">
                    <span class="show-sp"><?= lang('showXsNav') ?><i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i></span>
                    <span class="hidde-sp"><?= lang('hideXsNav') ?><i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i></span>
                </a>
                <div id="nav-categories">
                    <?php

                    function loop_tree($pages, $is_recursion = false)
                    {
                        ?>
                        <ul class="<?= $is_recursion === true ? 'children' : 'parent' ?>">
                            <?php
                            foreach ($pages as $page) {
                                $children = false;
                                if (isset($page['children']) && !empty($page['children'])) {
                                    $children = true;
                                }
                                ?>
                                <li>
                                    <?php if ($children === true) {
                                        ?>
                                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                    <?php } else { ?>
                                        <i class="fa fa-circle-o" aria-hidden="true"></i>
                                    <?php } ?>
                                    <a href="javascript:void(0);" data-categorie-id="<?= $page['id'] ?>" class="go-category left-side <?= isset($_GET['category']) && $_GET['category'] == $page['id'] ? 'selected' : '' ?>">
                                        <?= $page['name'] ?>
                                    </a>
                                    <?php
                                    if ($children === true) {
                                        loop_tree($page['children'], true);
                                    } else {
                                        ?>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                        <?php
                        if ($is_recursion === true) {
                            ?>
                            </li>
                            <?php
                        }
                    }

                    loop_tree($home_categories);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-9 eqHeight" id="products-side">
            <div class="alone title">
                <span><?= lang('products') ?></span>
            </div>
            <div class="product-sort gradient-color">
                <div class="row">
                    <div class="ord col-sm-4">
                        <div class="form-group">
                            <select class="selectpicker order form-control" data-order-to="order_new">
                                <option <?= isset($_GET['order_new']) && $_GET['order_new'] == "desc" ? 'selected' : '' ?> <?= !isset($_GET['order_new']) || $_GET['order_new'] == "" ? 'selected' : '' ?> value="desc"><?= lang('new') ?> </option>
                                <option <?= isset($_GET['order_new']) && $_GET['order_new'] == "asc" ? 'selected' : '' ?> value="asc"><?= lang('old') ?> </option>
                            </select>
                        </div>
                    </div>
                    <div class="ord col-sm-4">
                        <div class="form-group">
                            <select class="selectpicker order form-control" data-order-to="order_price" title="<?= lang('price_title') ?>..">
                                <option label="<?= lang('not_selected') ?>"></option>
                                <option <?= isset($_GET['order_price']) && $_GET['order_price'] == "asc" ? 'selected' : '' ?> value="asc"><?= lang('price_low') ?> </option>
                                <option <?= isset($_GET['order_price']) && $_GET['order_price'] == "desc" ? 'selected' : '' ?> value="desc"><?= lang('price_high') ?> </option>
                            </select>
                        </div>
                    </div>
                    <div class="ord col-sm-4">
                        <div class="form-group">
                            <select class="selectpicker order form-control" data-order-to="order_procurement" title="<?= lang('procurement_title') ?>..">
                                <option label="<?= lang('not_selected') ?>"></option>
                                <option <?= isset($_GET['order_procurement']) && $_GET['order_procurement'] == "desc" ? 'selected' : '' ?> value="desc"><?= lang('procurement_desc') ?> </option>
                                <option <?= isset($_GET['order_procurement']) && $_GET['order_procurement'] == "asc" ? 'selected' : '' ?> value="asc"><?= lang('procurement_asc') ?> </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if (!empty($products)) {
                $load::getProducts($products, 'col-sm-4 col-md-3', false);
            } else {
                ?>
                <script>
                    $(document).ready(function () {
                        ShowNotificator('alert-info', '<?= lang('no_results') ?>');
                    });
                </script>
                <?php
            }
            ?>
        </div>
    </div>
    <?php if ($links_pagination != '') { ?>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <?= $links_pagination ?>
            </div>
        </div>
    <?php } ?>
</div>