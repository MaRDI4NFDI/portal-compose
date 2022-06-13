#!/bin/bash

set +e

## Test if metrics are correctly produced for Prometheus and accessible

# This script has two parts:
# Without arguments, it first executes test_node_exporter on the host, then
# calls the script again (with the docker argument) from within docker and
# executes the corresponding tests that require the docker environment.

# test if node_exporter metrics can be accessed on
# ${HOST_NETWORK_IP}:9101/metrics --- i.e., on the host network, due to
# the docker setting network_mode=host.
# THIS MUST BE EXECUTED ON THE HOST, OUTSIDE OF THE DOCKER COMPOSE ECOSYSTEM
# Execute in the base path of portal-compose, where the .env file is located
test_node_exporter() {
    printf "  - test_node_exporter:\n"
    # source environment variables from .env file (ignore comments, quote strings)
    #  
    # 
    set -o allexport
    source <(printf '%s' "$(grep -v '^#' .env | sed 's/=\(.*\)$/="\1"/' | xargs -d '\n')")
    set +o allexport
    # printf '%s' "$BACKUP_SCHEDULE"

    if [[ -n "$HOST_NETWORK_IP" ]]; then
        printf '    OK: Found $HOST_NETWORK_IP=%s\n' "${HOST_NETWORK_IP}"
        metrics=$(curl --silent --show-error "${HOST_NETWORK_IP}:9101/metrics")
        # printf "curl status ($?)\n"
        # printf "\nmetrics=$metrics\n"
        if [[ -z "$metrics" ]]; then
            printf '    ERROR: No metrics found at %s:9101/metrics\n' "${HOST_NETWORK_IP}"
            return 1
        else
            printf '    OK: Found metrics at %s:9101/metrics\n' "${HOST_NETWORK_IP}"
        fi
    else
        printf "    ERROR: Environment variable \$HOST_NETWORK_IP was not set\n"
        return 1
    fi
}

# test if traefik metrics can be accessed within the internal docker network.
# TEST MUST RUN WITHIN DOCKER
test_traefik() {
    printf "  - test_traefik:\n"
    metrics=$(curl --silent --show-error  http://reverse-proxy:8082/metrics)
    if [[ $? == 6 ]]; then
        printf "    ** Note: test_traefik must be executed within docker **\n"
    fi
    if [[ -z "$metrics" ]]; then
        printf "    ERROR: No metrics found at http://reverse-proxy:8082/metrics\n"
        return 1
    else
        printf "    OK: Found metrics at http://reverse-proxy:8082/metrics\n"
    fi
}
################ main ################


if [[ -z "$1" ]]; then
    # first run, without arguments
    test_node_exporter
    docker exec mardi-selenium /bin/bash /test/test_metrics.sh docker
elif [[ "$1" == docker ]]; then
    # assume we're inside docker
    test_traefik
else
    printf 'ERROR: unknown command line argument "%s". Must be empty or "docker"\n' "$1"
fi
