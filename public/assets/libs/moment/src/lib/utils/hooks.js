export{hooks,setHookCallback};var hookCallback;function hooks(){return hookCallback.apply(null,arguments)}function setHookCallback(o){hookCallback=o}