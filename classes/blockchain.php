<?php

class Blockchain {
    public $chain = [];
    public $current_transactions = [];
    public $last_block = [];

    /**
     * Construct the chain
     * 
     * TODO: load the current chain from a data source. For demo purposes we are simply generating the genesis block every time
     * 
     * @return void
     */
    public function __construct() {
        $this->new_block(100, 1);
    }

    /**
     * Add a new block to the chain
     * 
     * @param int $proof The proof given by the "Proof of Work" algorithm
     * @param string $previous_hash Hash of the previous block 
     * @return array $block The newly created block
     */
    public function new_block($proof, $previous_hash = null) {
        $block = [
            'index' => count($this->chain) + 1,
            'timestamp' => time(),
            'transactions' => $this->current_transactions,
            'proof' => $proof,
            'previous_hash' => !is_null($previous_hash) ? $previous_hash : $this->hash_block($this->get_last_block())
        ];

        $this->current_transactions = [];
        $this->chain[] = $block;

        return $block;
    }

    /**
     * Create a new transaction to go into the next block
     * 
     * TODO: validate/verify transaction data
     * 
     * @param string $sender Senders address
     * @param string $recipient Recipients address
     * @param int $amount Transaction amount
     * @return int The next valid index
     */
    public function new_transaction($sender, $recipient, $amount) {
        $this->current_transactions[] = [
            'sender' => $sender,
            'recipient' => $recipient,
            'amount' => $amount,
        ];

        $last_block = $this->get_last_block();
        return $last_block['index'] + 1;
    }

    /**
     * Simple proof of work algo 
     * 
     * @param int $last_proof Previous proof
     * @return int 
     */
    function proof_of_work($last_proof) {
        $proof = 0;

        while (self::valid_proof($last_proof, $proof) === false) {
            $proof++;
        }

        return $proof;
    }

    /**
     * Validate the Proof

     * Find a hash that contains four leading zeros. 
     * 
     * @param int $last_proof Previous proof
     * @param int $proof Current proof
     * @return bool
     */
    public static function valid_proof($last_proof, $proof) {
        $guess = $last_proof . $proof;
        $guess = hash("SHA256", $guess);

        return (substr($guess, 0, 4) === '0000');
    }

    /**
     * Generate a blocks hash (SHA-256)
     * 
     * @param array $block The block to generate the hash for
     * @return string The hashed value of the block
     */
    public static function hash_block($block) {
        asort($block);
        return hash("SHA256", json_encode($block));
    }

    /**
     * Return the last block in the chain
     * 
     * @return array
     */
    public function get_last_block() {
        return $this->chain[count($this->chain)-1];
    }
}
