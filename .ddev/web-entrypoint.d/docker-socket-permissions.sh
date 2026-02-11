#!/bin/bash
# Make Docker socket accessible to the web container user
if [ -S /var/run/docker.sock ]; then
    sudo chmod 666 /var/run/docker.sock || true
fi
