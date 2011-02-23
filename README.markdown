# PHP Dependency checker

Different applications end up depending on various non-standard functionality. Many of these applications end up running checks for the existence of these extensions on every request, adding unnecessary logic and overhead.

It's easier to simply keep track of extension dependencies in a single file run on the server before deployment. If it exits clean, it's OK to continue deployment. If not, something is missing from the environment and the deployment should be halted.

## Setup

In the script, there are many calls to `show()`. The first parameter is simply a label for the extension, and the second param is some sort of check (returning `true`, `false`, or `"skip"`).  The `label` calls are purely aesthetic.

Example:

	label('Text/Unicode');
		show('mbstring extension', extension_loaded('mbstring'));
		if (extension_loaded('mbstring'))
			show('mbstring overload is disabled', !(ini_get('mbstring.func_overload') & MB_OVERLOAD_STRING));
		else
			show('mbstring overload is disabled', 'skip');

Depending on your environment settings, you may see a result like this:

	-=[ Text/Unicode ]=-
	mbstring extension            [  OK  ]
	mbstring overload is disabled [  OK  ]

Or, you may see something like this:

	-=[ Text/Unicode ]=-
	mbstring extension            [FAILED]
	mbstring overload is disabled [ SKIP ]



## Usage

At the most basic level, simply run the script from the command line:

`php check.php`

Any missing functionality will be listed.

To integrate with a deployment script, check the return code of the script execution:

	<?php
	exec('php check.php', $out, $return);
	if ($return === 0) {
		// Continue with deployment
	}
	else {
		// Something was missing, halt
		// Maybe email implode("\n", $out) to a sysadmin.
	}

