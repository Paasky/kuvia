<?php
/**
 * Created by PhpStorm.
 * User: pekko.tuomisto
 * Date: 23.9.2018
 * Time: 13.13
 */

namespace App\Permissions;


use App\Managers\CollageManager;
use App\Managers\ProductManager;
use App\Managers\SubscriptionPlanManager;
use App\Managers\UserManager;
use App\System;
use Illuminate\Validation\UnauthorizedException;

class SalePermissions extends CommonPermissions
{
    public static function viewAllSubscriptions() : void
    {
        self::verifyIsAdmin();
    }

    public static function createSubscription(\App\SubscriptionPlan $subscriptionPlan) : void
    {
        self::verifyIsLoggedIn();

        if(!SubscriptionPlanManager::isActive($subscriptionPlan)){
            throw new UnauthorizedException('The subscription plan has been discontinued.');
        }

        if(UserManager::hasActiveSubscription(System::getLoggedInUser(), $subscriptionPlan)){
            throw new UnauthorizedException('You already have this subscription.');
        }
    }

    public static function viewSubscription(\App\Subscription $subscription) : void
    {
        self::verifyIsLoggedIn();
        self::verifyIsAdminOrCurrentUser($subscription->getUser());

    }

    public static function editSubscription(\App\Subscription $subscription) : void
    {
        self::verifyIsLoggedIn();
        self::verifyIsUser($subscription->getUser());
    }

    public static function deleteSubscription() : void
    {
        self::verifyIsLoggedIn();

        throw new UnauthorizedException('Can\'t delete subscriptions.');
    }

    /** ***** PURCHASES ***** */

    public static function viewAllPurchases() : void
    {
        self::verifyIsAdmin();
    }

    public static function createPurchase(\App\Product $product, \App\Collage $collage) : void
    {
        self::verifyIsUser($collage->getUser());

        if(!ProductManager::isActive($product)){
            throw new UnauthorizedException('The product has been discontinued.');
        }

        if(!CollageManager::hasActiveProduct($collage, $product)){
            throw new UnauthorizedException('The product is already active for this collage.');
        }
    }

    public static function viewPurchase(\App\Purchase $purchase) : void
    {
        self::verifyIsAdminOrCurrentUser($purchase->getUser());
    }

    public static function editPurchase() : void
    {
        throw new UnauthorizedException('Can\'t modify purchases.');
    }

    public static function deletePurchase() : void
    {
        throw new UnauthorizedException('Can\'t modify purchases.');
    }

    /** ***** SUBSCRIPTION PLANS ***** */

    public static function viewAllSubscriptionPlans() : void
    {
        self::verifyIsAdmin();
    }

    public static function createSubscriptionPlan() : void
    {
        self::verifyIsAdmin();
    }

    public static function viewSubscriptionPlan(\App\SubscriptionPlan $subscriptionPlan) : void
    {
        self::verifyIsLoggedIn();

        if(!SubscriptionPlanManager::isActive($subscriptionPlan) && !UserManager::isAdmin(System::getLoggedInUser())){
            throw new UnauthorizedException('The product has been discontinued.');
        }
    }

    public static function editSubscriptionPlan(\App\SubscriptionPlan $subscriptionPlan) : void
    {
        self::verifyIsAdmin();

        if($subscriptionPlan->subscriptions()){
            throw new UnauthorizedException('Can\'t modify a subscription plan with subscriptions.');
        }
    }

    public static function toggleSubscriptionPlan() : void
    {
        self::verifyIsAdmin();
    }

    public static function deleteSubscriptionPlan(\App\SubscriptionPlan $subscriptionPlan) : void
    {
        self::verifyIsAdmin();

        if($subscriptionPlan->subscriptions()){
            throw new UnauthorizedException('Can\'t modify a subscription plan with subscriptions.');
        }
    }

    /** ***** PRODUCTS ***** */

    public static function viewAllProducts() : void
    {
        self::verifyIsAdmin();
    }

    public static function createProduct() : void
    {
        self::verifyIsAdmin();
    }

    public static function viewProduct(\App\Product $product) : void
    {
        self::verifyIsLoggedIn();

        if(!ProductManager::isActive($product) && !UserManager::isAdmin(System::getLoggedInUser())){
            throw new UnauthorizedException('The product has been discontinued.');
        }
    }

    public static function editProduct(\App\Product $product) : void
    {
        self::verifyIsAdmin();

        if($product->purchases()){
            throw new UnauthorizedException('Can\'t modify a product with purchase history.');
        }
    }

    public static function toggleProduct() : void
    {
        self::verifyIsAdmin();
    }

    public static function deleteProduct(\App\Product $product) : void
    {
        self::verifyIsAdmin();

        if($product->purchases()){
            throw new UnauthorizedException('Can\'t modify a product with purchase history.');
        }
    }
}
