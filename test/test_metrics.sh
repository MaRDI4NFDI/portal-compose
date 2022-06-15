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
    echo "  - test_node_exporter:"
    # safely source environment variables from .env file
    # NOTE: set -a; source ...; set +a loads the sourced variables as Env Vars, not required here
    # quick explanation:
    # - source executes a file
    # - <(command) is a bash process substitution from which it is possible to read
    #   (like from STDIN), in this case, source reads from the substitution (instead of
    #   a file)
    # - grep deletes commented lines
    # - sed puts variable values inside double quotes
    # - xargs creates a single line from the ensemble of lines
    source <(grep -v '^#' .env | sed 's/=\(.*\)$/="\1"/' | xargs -d '\n')

    if [[ -n "$HOST_NETWORK_IP" ]]; then
        echo "    OK: Found \$HOST_NETWORK_IP=${HOST_NETWORK_IP}"
        metrics=$(curl --silent --show-error "${HOST_NETWORK_IP}:9101/metrics")
        if [[ -z "$metrics" ]]; then
            echo "    ERROR: No metrics found at ${HOST_NETWORK_IP}:9101/metrics"
            return 1
        else
            echo "    OK: Found metrics at ${HOST_NETWORK_IP}:9101/metrics"
        fi
    else
        echo "    ERROR: Environment variable \$HOST_NETWORK_IP was not set"
        return 1
    fi
}

# test if traefik metrics can be accessed within the internal docker network.
# TEST MUST RUN WITHIN DOCKER
test_traefik() {
    echo "  - test_traefik:"
    metrics=$(curl --silent --show-error  http://reverse-proxy:8082/metrics)
    if [[ $? == 6 ]]; then
        echo "    ** Note: test_traefik must be executed within docker **"
    fi
    if [[ -z "$metrics" ]]; then
        echo "    ERROR: No metrics found at http://reverse-proxy:8082/metrics"
        return 1
    else
        echo "    OK: Found metrics at http://reverse-proxy:8082/metrics"
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
    echo "ERROR: unknown command line argument \"$1\". Must be empty or \"docker\""
fi
