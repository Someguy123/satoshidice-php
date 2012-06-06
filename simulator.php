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
	$data = simulpay($bet, $chance, $rand);
	$balance = $balance - $bet;
	echo "{$balance} - Betted...\n";
	if ($data)
	{
		if ($data == "MAX_BET")
		{
			$bet = $payamt; // Drop back to minbet
			echo "!-!-! WARNING: MAX BET HIT - DROPPING TO MINBET !-!-!\n\n";
		} else
		{
			$bet = $payamt;
			$won++;
			$balance = $balance + $data;
			echo "{$balance} - WON: {$won}/{$i}\n\n";
		}
	} else
	{
		$bet = $bet * 2; // Double bet
		echo "{$balance} - LOSE!\n\n";
	}
}

?>
