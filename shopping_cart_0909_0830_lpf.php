<?php
// 代码生成时间: 2025-09-09 08:30:02
use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Mvc\Dispatcher;

// Shopping Cart Model
class ShoppingCart extends Model
{
    // Cart items relation
    public function initialize()
    {
        $this->hasMany('cart_id', 'CartItem', 'cart_id', ['alias' => 'CartItems']);
    }

    // Add item to cart
    public function addItem($productId, $quantity)
    {
        // Check if the item already exists in the cart
        $item = CartItem::findFirst(["cart_id = ?0 AND product_id = ?1", 'bind' => [$this->id, $productId]]);

        if ($item) {
            // If item exists, update quantity
            $item->quantity += $quantity;
            if (!$item->save()) {
                $this->handleSaveError($item);
                return false;
            }
        } else {
            // If item does not exist, create a new one
            $item = new CartItem();
            $item->cart_id = $this->id;
            $item->product_id = $productId;
            $item->quantity = $quantity;

            if (!$item->save()) {
                $this->handleSaveError($item);
                return false;
            }
        }

        return true;
    }

    // Remove item from cart
    public function removeItem($productId)
    {
        $item = CartItem::findFirst(["cart_id = ?0 AND product_id = ?1", 'bind' => [$this->id, $productId]]);

        if ($item) {
            return $item->delete();
        }

        return false;
    }

    // Handle save errors
    private function handleSaveError($item)
    {
        foreach ($item->getMessages() as $message) {
            throw new \Exception($message->getMessage());
        }
    }
}

// Cart Item Model
class CartItem extends Model
{
    public $cart_id;
    public $product_id;
    public $quantity;

    public function validation()
    {
        $validator = new Validation();

        // Validate presence of quantity
        $validator->add('quantity', new PresenceOf(['message' => 'Quantity is required']));

        // Validate quantity is numeric
        $validator->add('quantity', new Numericality(['message' => 'Quantity must be numeric']));

        return $this->validate($validator);
    }
}

// Example usage
try {
    // Assume $userId is obtained from session or user authentication
    $userId = 1;
    $cart = ShoppingCart::findFirst(["user_id = ?0", 'bind' => [$userId]]);

    if (!$cart) {
        $cart = new ShoppingCart();
        $cart->user_id = $userId;
        $cart->save();
    }

    // Add item to cart
    $cart->addItem(101, 2); // Product ID 101, Quantity 2

    // Remove item from cart
    $cart->removeItem(102); // Product ID 102

} catch (Exception $e) {
    echo 'Error: ',  $e->getMessage(), "\
";
}
