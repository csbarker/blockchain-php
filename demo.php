<?php

include('classes/blockchain.php');

$blockchain = new Blockchain();

// transactions to be included in the next block
$blockchain->new_transaction('foo', 'recipient1', 100);
$blockchain->new_transaction('foo', 'recipient2', 50);
$blockchain->new_transaction('foo', 'recipient3', 25);
$blockchain->new_transaction('foo', 'recipient4', 15);
$blockchain->new_transaction('foo', 'recipient5', 10);

// "Mine" the block
$last_block = $blockchain->get_last_block();
$last_proof = $last_block['proof'];

$proof = $blockchain->proof_of_work($last_proof);
$previous_hash = $blockchain->hash_block($last_block);

$blockchain->new_block($proof, $previous_hash);

/*
    ...more transactions and mining
*/

// Output the chain
header('Content-type:application/json;charset=utf-8');
echo json_encode($blockchain->chain);