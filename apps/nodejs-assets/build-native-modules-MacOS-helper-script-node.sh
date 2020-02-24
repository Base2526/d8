#!/bin/bash
      # Helper script for Gradle to call node on macOS in case it is not found
      export PATH=$PATH:/usr/local/lib/node_modules/npm/node_modules/npm-lifecycle/node-gyp-bin:/Users/somkid/Desktop/docker-project/d8/apps/node_modules/nodejs-mobile-react-native/node_modules/.bin:/Users/somkid/Desktop/docker-project/d8/apps/node_modules/.bin:/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin
      node $@
    