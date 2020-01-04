NEXT PHASE:
* server side cookie sessions
* visible query builder
* check s3 again, blobcontroller
* architecture foldermembership, usergroupmembership
* blob <> assetcontroller <> foldercontent relationship
* usersgroups <> foldersMembership <> folder
* UserGroupController.php && Resource
* facebook login integration
* FolderMembershipsController.php

PHASE + 1:
* censor github settings history
* discriminator pattern doctrine
* s3 stream video
* fetch blobs of thumbnail/parameter size (?size=300x200) (aws lambda)
* remove unneeded packages like doctrine/annotations
* services to daemons
* notification socket server
* create phantom accounts with 3rd party ids, schema: id email facebookId googleId isClaimed
* captcha for registering
* garbage collector for sessions, blobs