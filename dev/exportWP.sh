docker-compose run --rm -v $(pwd)/backups/wp:/backups cli wp export --dir=/backups --post_type=user_awards_cpt