<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url('assets/bootstrap-select-1.12.1/bootstrap-select.min.css') ?>">
<div class="inner-nav">
    <div class="container">
        <?= lang('home') ?> <span class="active"> > <?= lang('checkout') ?></span>
    </div>
</div>
<div class="container" id="checkout-page">
    <?php
    if (isset($cartItems['array']) && $cartItems['array'] != null) {
        ?> 
        <form method="POST" id="goOrder">
            <div class="row">
                <div class="col-sm-6 left-side">
                    <h4><?= lang('billing_details') ?></h4>
                    <div class="title alone">
                        <span><?= lang('checkout') ?></span>
                    </div>
                    <?php
                    if ($this->session->flashdata('submit_error')) {
                        ?>
                        <hr>
                        <div class="alert alert-danger">
                            <h4><span class="glyphicon glyphicon-alert"></span> <?= lang('finded_errors') ?></h4>
                            <?php
                            foreach ($this->session->flashdata('submit_error') as $error) {
                                echo $error . '<br>';
                            }
                            ?>
                        </div>
                        <hr>
                        <?php
                    }
                    ?>
                    <div class="payment-type-box">
                        <select class="selectpicker payment-type" data-style="btn-green" name="payment_type">
                            <?php if ($cashondelivery_visibility == 1) { ?>
                                <option value="cashOnDelivery"><?= lang('cash_on_delivery') ?> </option>
                            <?php } if (filter_var($paypal_email, FILTER_VALIDATE_EMAIL)) { ?>
                                <option value="PayPal"><?= lang('paypal') ?> </option>
                            <?php } if ($bank_account['iban'] != null) { ?>
                                <option value="Bank"><?= lang('bank_payment') ?> </option>
                            <?php } ?>
                        </select>
                        <span class="top-header text-center"><?= lang('choose_payment') ?></span>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="firstNameInput"><?= lang('first_name') ?> (<sup><?= lang('requires') ?></sup>)</label>
                            <input id="firstNameInput" class="form-control" name="first_name" value="<?= htmlspecialchars(@$_POST['first_name']) ?>" type="text" placeholder="<?= lang('first_name') ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="lastNameInput"><?= lang('last_name') ?> (<sup><?= lang('requires') ?></sup>)</label>
                            <input id="lastNameInput" class="form-control" name="last_name" value="<?= htmlspecialchars(@$_POST['last_name']) ?>" type="text" placeholder="<?= lang('last_name') ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="emailAddressInput"><?= lang('email_address') ?> (<sup><?= lang('requires') ?></sup>)</label>
                            <input id="emailAddressInput" class="form-control" name="email" value="<?= htmlspecialchars(@$_POST['email']) ?>" type="text" placeholder="<?= lang('email_address') ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="phoneInput"><?= lang('phone') ?> (<sup><?= lang('requires') ?></sup>)</label>
                            <input id="phoneInput" class="form-control" name="phone" value="<?= htmlspecialchars(@$_POST['phone']) ?>" type="text" placeholder="<?= lang('phone') ?>">
                        </div>
                        <div class="form-group col-sm-12">
                            <label for="addressInput"><?= lang('address') ?> (<sup><?= lang('requires') ?></sup>)</label>
                            <textarea id="addressInput" name="address" class="form-control" rows="3"><?= htmlspecialchars(@$_POST['address']) ?></textarea>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="cityInput"><?= lang('city') ?> (<sup><?= lang('requires') ?></sup>)</label>
                            <input id="cityInput" class="form-control" name="city" value="<?= htmlspecialchars(@$_POST['city']) ?>" type="text" placeholder="<?= lang('city') ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="postInput"><?= lang('post_code') ?></label>
                            <input id="postInput" class="form-control" name="post_code" value="<?= htmlspecialchars(@$_POST['post_code']) ?>" type="text" placeholder="<?= lang('post_code') ?>">
                        </div>
                        <div class="form-group col-sm-12">
                            <label for="notesInput"><?= lang('notes') ?></label>
                            <textarea id="notesInput" class="form-control" name="notes" rows="3"><?= htmlspecialchars(@$_POST['notes']) ?></textarea>
                        </div>
                    </div>
                    <?php if ($codeDiscounts == 1) { ?>
                        <div class="discount">
                            <label><?= lang('discount_code') ?></label>
                            <input class="form-control" name="discountCode" value="<?= htmlspecialchars(@$_POST['discountCode']) ?>" placeholder="<?= lang('enter_discount_code') ?>" type="text">
                            <a href="javascript:void(0);" class="btn btn-default" onclick="checkDiscountCode()"><?= lang('check_code') ?></a>
                        </div>
                    <?php } ?>
                    <div>
                        <a href="javascript:void(0);" class="btn go-order" onclick="document.getElementById('goOrder').submit();" class="pull-left">
                            <?= lang('custom_order') ?> 
                        </a>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h4><?= lang('your_order') ?></h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-products">
                            <thead>
                                <tr>
                                    <th><?= lang('product') ?></th>
                                    <th><?= lang('title') ?></th>
                                    <th><?= lang('quantity') ?></th>
                                    <th><?= lang('price') ?></th>
                                    <th><?= lang('total') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems['array'] as $item) { ?>
                                    <tr>
                                        <td class="relative">
                                            <input type="hidden" name="id[]" value="<?= $item['id'] ?>">
                                            <input type="hidden" name="quantity[]" value="<?= $item['num_added'] ?>">
                                            
                                            <?php 
                                                $productImage = base_url('/attachments/no-image-frontend.png');
                                                if(is_file('attachments/shop_images/' . $item['image'])) {
                                                    $productImage = base_url('/attachments/shop_images/' . $item['image']);
                                                }
                                            ?>
                                            <img class="product-image" src="<?= $productImage ?>" alt="">
                                            
                                            <a href="<?= base_url('home/removeFromCart?delete-product=' . $item['id'] . '&back-to=checkout') ?>" class="btn btn-xs btn-danger remove-product">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </a>
                                        </td>
                                        <td><a href="<?= LANG_URL . '/' . $item['url'] ?>"><?= $item['title'] ?></a></td>
                                        <td>
                                            <a class="btn btn-xs btn-primary refresh-me add-to-cart <?= $item['quantity'] <= $item['num_added'] ? 'disabled' : '' ?>" data-id="<?= $item['id'] ?>" href="javascript:void(0);">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </a>
                                            <span class="quantity-num">
                                                <?= $item['num_added'] ?>
                                            </span>
                                            <a class="btn  btn-xs btn-danger" onclick="removeProduct(<?= $item['id'] ?>, true)" href="javascript:void(0);">
                                                <span class="glyphicon glyphicon-minus"></span>
                                            </a>
                                        </td>
                                        <td><?= $item['price'] . CURRENCY ?></td>
                                        <td><?= $item['sum_price'] . CURRENCY ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="4" class="text-right"><?= lang('total') ?></td>
                                    <td>
                                        <span class="final-amount"><?= $cartItems['finalSum'] ?></span><?= CURRENCY ?>
                                        <input type="hidden" class="final-amount" name="final_amount" value="<?= $cartItems['finalSum'] ?>">
                                        <input type="hidden" name="amount_currency" value="<?= CURRENCY ?>">
                                        <input type="hidden" name="discountAmount" value="">
                                    </td>
                                </tr>
                                
                                <?php
                                $total_parsed = str_replace(' ', '', str_replace(',', '', $cartItems['finalSum']));
                                if((int)$shippingAmount > 0 && ((int)$shippingOrder > $total_parsed)) {
                                ?>
                                <tr>
                                    <td colspan="4" class="text-right"><?= lang('shipping') ?></td>
                                    <td>
                                        <span class="final-amount"><?= (int)$shippingAmount ?></span><?= CURRENCY ?>
                                    </td>
                                </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php } else { ?>
    <div class="alert alert-info"><?= lang('no_products_in_cart') ?></div>
    <?php
}
if ($this->session->flashdata('deleted')) {
    ?>
    <script>
        $(document).ready(function () {
            ShowNotificator('alert-info', '<?= $this->session->flashdata('deleted') ?>');
        });
    </script>
<?php } if ($codeDiscounts == 1 && isset($_POST['discountCode'])) { ?>
    <script>
        $(document).ready(function () {
            checkDiscountCode();
        });
    </script>
<?php } ?>
<script src="<?= base_url('assets/bootstrap-select-1.12.1/js/bootstrap-select.min.js') ?>"></script>