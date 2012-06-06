<?php

include ("function.php");

$rounds = $_SERVER['argv'][1];
$payamt = $_SERVER['argv'][2];
$chance = $_SERVER['argv'][3];
// Predefine variables
$balance = 100;
$i = 0;
$bet = $payamt;
$won = 0;
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
	echo "\n\n{$balance} - Round {$i} - Betting {$bet}\n";
	if ($bet > $maxbet[$chance])
	{
		// We've hit the maximum bet for this chance, lets drop back to min_bet
		echo "Dropping back to minimum bet ( {$payamt} ), we've gone over the max bet.\n";
		$bet = $payamt; // Set back to minbet
	}
	$data = simulpay($bet, $chance, $rand);
	$balance = $balance - $bet;
	echo "{$balance} - Betted...\n";
	if ($data) // If we get a response
	{
		$bet = $payamt;
		$won++;
		$balance = $balance + $data;
		echo "{$balance} - WON: {$won}/{$i}\n\n";
	} else
	{
		$bet = $bet * 2; // Double bet
		echo "{$balance} - LOSE!\n\n";
	}
}

?>
