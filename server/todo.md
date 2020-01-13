NEXT PHASE:
first folder controller, resource, test with reg and unreg user
* controllers for: Folder, FolderMembership, UserGroup
* server side cookie sessions
* check s3 again, blobcontroller
* blob <> assetcontroller <> foldercontent relationship
* UserGroupController.php && Resource
* facebook login integration
* limit User(s)Controller api with permissions

PHASE + 1:
* censor github settings history
* discriminator pattern doctrine
* s3 stream video
* fetch blobs of thumbnail/parameter size (?size=300x200) (aws lambda)
* remove unneeded packages like doctrine/annotations
* services to daemons
* notification socket server
* create phantom accounts with 3rd party ids, schema: id email facebookId googleId isClaimed
* captcha for registering & lock login after 3 attempts
* garbage collector for sessions, blobs