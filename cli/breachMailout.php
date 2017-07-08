<?php

if ( PHP_SAPI !== 'cli' ) {
	exit;
}

$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = realpath( __DIR__ . '/../../../core' );
}
require "$IP/maintenance/Maintenance.php";

class RWBreachMailout extends Maintenance {
	public function execute() {

		$subject = "Your user account data was compromised on RationalWiki.org";

		$intro = <<<EOT
In February 2017, RationalWiki's server was compromised and its database was 
downloaded. The database includes user emails, IP activity, and passwords 
that could be breached. Note that user passwords were hashed. However, since
we used an old version of MediaWiki, this hashing was weak. For more
information about password hashing, see:

<https://www.mediawiki.org/wiki/Manual:User_table#user_password>
<https://www.mediawiki.org/wiki/Manual:Hashing>

Please change your password on RationalWiki.org. If you use your RationalWiki
password on any other website, please change your password there as well.

For more information, see the RationalWiki tech blog:
<http://rationalwiki.blogspot.com/2017/06/server-upgrade-data-breach-on-old.html>

And the Saloon bar:
<http://rationalwiki.org/wiki/RationalWiki:Saloon_bar#Security_breach_announcement_.28sticky.29>

EOT;

		$multiMessage = "You are receiving this message because you registered accounts at\n" .
			"RationalWiki with the usernames:\n\n";

		$singleMessage = "You are receiving this message because you registered an account at\n" .
			"RationalWiki with the username";

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->query( 'SELECT ' .
			'user_email, GROUP_CONCAT(user_name SEPARATOR \'|\') AS names ' .
			'FROM user ' .
			'WHERE user_password_expires IS NOT NULL ' .
			'AND user_email_authenticated IS NOT NULL ' .
			'GROUP BY user_email ' .
			'ORDER BY user_email',
			__METHOD__ );

		$sender = new MailAddress( 'security@rationalwiki.org', 'RationalWiki' );

		foreach ( $res as $row ) {
			$emailParts = explode( '@', $row->user_email, 2 );
			if ( $emailParts < 2 ) {
				continue;
			}
			if ( substr_count( $emailParts[0], '.' ) > 5 && $emailParts[1] === 'gmail.com' ) {
				// Skip ~843 spambot email addresses
				continue;
			}

			$names = explode( "|", $row->names );

			$message = $intro;
			if ( count( $names ) > 1 ) {
				$message .= "\n" . $multiMessage . wordwrap( implode( ", ", $names ) );
				$name = null;
			} else {
				$message .= "\n{$singleMessage} \"{$names[0]}\"\n";
				$name = $names[0];
			}

			UserMailer::send(
				new MailAddress( $row->user_email, $name ),
				$sender,
				$subject,
				$message );

			echo "{$row->user_email}\n";
		}
	}
}

$maintClass = 'RWBreachMailout';
require_once RUN_MAINTENANCE_IF_MAIN;
