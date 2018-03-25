#!/bin/bash

echo "\nHere's an example of a Bash script running via Composer."
echo "Let's see if you've got WP-CLI installed:\n"

HAS_WPCLI=`wp --version`

if [[ HAS_WPCLI ]]; then
    echo "${HAS_WPCLI}\n"
    echo "Yep, you've got it!"
fi
