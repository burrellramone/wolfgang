<?php
use Wolfgang\Util\CliWriter;

function __ (): string {
	return call_user_func_array(['Wolfgang\I18N\I18N', '__'], func_get_args());
}

function lock_db() {
	$phinx_interactive = getenv('PHINX_INTERACTIVE');
	$phinx_interactive = $phinx_interactive === false ? true : (bool)(intval($phinx_interactive));
	

	if ($phinx_interactive) {
		do {
			$answer = readline("Continue? (Y=yes,N=no): ");
			
			if($answer != 'Y' && $answer != 'N'){
				CliWriter::error("Invalid Answer");
			} else if($answer == 'N') {
				CliWriter::info("Ok");
				exit;
			} else {
				break;
			}
			
		} while(true);
	}
	
	file_put_contents(DOCUMENT_ROOT . "/db.lock", time());
}

function unlock_db(){
	unlink(DOCUMENT_ROOT . "/db.lock");
}