#!/bin/bash
set -e

sleep 5

/entrypoint.sh apache2-foreground &
ENTRYPOINT_PID=$!

sleep 5

BOT_NAMES_ARRAY=(${BOT_NAMES:-cran zbmath wikidata openml zenodo polydb crossref arxiv})

echo "Creating importer users..."
for bot_name in "${BOT_NAMES_ARRAY[@]}"; do
    echo "Creating bot user: ${bot_name}"
    
    BOTUSER_NAME="${bot_name}-user"
    BOTUSER_PW="${bot_name}-password"
    
    # Create and promote the bot user
    php /var/www/html/w/maintenance/createAndPromote.php "$BOTUSER_NAME" "$BOTUSER_PW" --bot
    
    echo "Successfully created bot user: $BOTUSER_NAME"
done

kill $ENTRYPOINT_PID
exit 0