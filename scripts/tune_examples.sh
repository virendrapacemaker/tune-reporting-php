#!/bin/bash

if [ $# -ne 1 ]; then
    echo usage: $0 api_key
    exit 1
fi

api_key=$1

php ./examples/TuneExamples.php $api_key
