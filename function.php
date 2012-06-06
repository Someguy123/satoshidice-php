<?php

/**
 * roll_dice($txid)
 * Generates a random number that can be verified if
 * you have both the secret and the TXID.
 * 
 * @param $txid - Anything will do, but a txid
 * appearing string will be the most reliable.
 *
 * @returns 1-65356
 */
function roll_dice($txid) {
        $secret = "Jfy37yt37hh78dyf732hs8jdjh3818iejf9402Cmj"; // Secret for generating the hashes
        $hmac = hash_hmac('sha512', $txid, $secret); // Hash the secret and TXID
        return hexdec(substr($hmac, 0, 4)); // Take the first 4 bytes and convert it to decimal.
}
/**
 *
 * This array is to determine the lessthans for each option
 * 
 */
$pcent = array(
	"QUAR" => 16000,
	"HALF" => 32000,
	"3QUAR" => 48000
);
/**
 *
 * This array is to determine the max bets for each option
 * 
 */
$maxbet = array(
    "QUAR" => 74.9726,
    "HALF" => "100",
    "3QUAR" => "100"
);
/**
 *
 * This array is to determine the win multipliers for each option
 * 
 */
$payout = array(
	"QUAR" => 4.019,
	"HALF" => 2.012,
	"3QUAR" => 1.343
);


/**
 * simulpay($payamt, $chance, $tx)
 * 
 * This function simulates a game of satoshidice.
 * It rolls the dice with the appropriate chance,
 * and then *'s their payment by the appropriate
 * multiplier on win.
 * 
 * @param mixed $payamt - Payment Amount
 * @param mixed $chance - A chance determined in the above arrays
 * @param mixed $tx - The TXID (can be any random string of any length)
 * @return string "MAX_BET" if your stakes are too high
 * @return float - Your winnings on win.
 * @return false on loss
 */
function simulpay($payamt, $chance, $tx) {
	global $pcent;
	global $payout;
    global $maxbet;
    if($payamt > $maxbet[$chance])
        return "MAX_BET";
    else
    { 
    	// Check if they won
    	if(roll_dice($tx) < $pcent[$chance])
    		// Return their winnings
    		return $payamt * $payout[$chance];
    	else
    		return false;
    }
}
?>
