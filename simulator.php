<?php

include ("function.php");

$rounds = $_SERVER['argv'][1];
$payamt = $_SERVER['argv'][2];
$chance = $_SERVER['argv'][3];
// Predefine variables
$balance = 1;
$i = 0;
$bet = $payamt;
$won = 0;
$currwin = 0;
$bestwin = 0;
$tx = 0.0005; // Change this to the current average fee per TX.
$totaltx = 0;
while ($i < $rounds)
{
	if ($bet > $balance)
	{
		if ($payamt > $balance)
		{
			die("We're fucked");
		} else
		{
			echo "Can't do this bet. Dropping back to minbet!";
			$bet = $payamt;
		}
	}
	$i++;
	$rand = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
		5)), 0, 64);
	echo "\n{$balance} - Round {$i} - Betting {$bet} + {$tx} txfee\n";
	if ($bet > $maxbet[$chance])
	{
		// We've hit the maximum bet for this chance, lets drop back to min_bet
		echo "Dropping back to minimum bet ( {$payamt} ), we've gone over the max bet.\n";
		$bet = $payamt; // Set back to minbet
	}
	$data = simulpay($bet, $chance, $rand);
	$balance = $balance - $bet - $tx;
    $totaltx = $totaltx + $tx;
	echo "{$balance} - Betted... (Total TXfees: {$totaltx})\n";
	if ($data) // If we get a response
	{
		$bet = $payamt;
		$won++;
		$currwin++;
		$balance = $balance + $data;
		$bestwin = ($currwin > $bestwin) ? $currwin : $bestwin;
		echo "{$balance} - WON: {$won}/{$i}\n";
	} else
	{
		$currwin = 0;
		$bet = $bet * 2; // Double bet
		echo "{$balance} - LOSE!\n";
	}
	echo "Current win streak: " . $currwin . "\nBest win streak: " . $bestwin . "\n\n";
}
?>
