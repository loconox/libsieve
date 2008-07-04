<?php

require_once('class.token.php');

class SieveException extends Exception
{
	public function __construct(Token $token, $arg)
	{
		$message = 'undefined sieve exception';

		if (is_string($arg))
		{
			$message = $arg;
		}
		else
		{
			if (is_array($arg))
			{
				$type = Token::typeString(array_shift($arg));
				foreach($arg as $t)
				{
					$type .= ' or '. Token::typeString($t);
				}
			}
			else
			{
				$type = Token::typeString($arg);
			}

			$tokenType = Token::typeString($token->type);
			$message = "$tokenType where $type expected near ". $token->text;
		}

		parent::__construct('line '. $token->line .": $message");
	}
}

?>