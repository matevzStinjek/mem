NEXT PHASE:
* test user permissions
* discriminator pattern doctrine (users)
* server side cookie sessions
* check s3 again, blobcontroller
* architecture foldermembership, usergroupmembership
* blob <> assetcontroller <> foldercontent relationship
* usersgroups <> foldersMembership <> folder
* UserGroupController.php && Resource
* FolderMembershipsController.php

PHASE + 1:
* censor github settings history
* facebook login integration
* s3 stream video
* fetch blobs of thumbnail/parameter size (?size=300x200) (aws lambda)
* remove unneeded packages like doctrine/annotations
* services to daemons
* notification socket server
* create phantom accounts with 3rd party ids, schema: id email facebookId googleId isClaimed