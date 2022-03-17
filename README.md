# aws

AWS API utilities to interact with S3 services (S3, Scaleway...)

# Usage

Install composer dependencies :

    composer install

Configure environment by creating a .env file as :

    AWS_ENDPOINT="https://s3.fr-par.scw.cloud"
    AWS_REGION="fr-par"
    AWS_KEY="YOUR-KEY"
    AWS_SECRET="YOUR-SECRET"
    AWS_BUCKET="YOUR-BUCKET"

Cancel unfinished multipart uploads :

    php aws cancel-uploads

Update bucket config to auto cancel unfinished multipart uploads after 1 day :

    php aws cancel-uploads-config
