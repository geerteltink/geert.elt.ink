#!groovy

node {
    try {
        stage 'Build'
        checkout scm
        sh 'rm -rf data/build/*'
        sh 'rm -rf data/cache/*'
        sh 'rm -rf data/import/*'
        sh 'rm -rf data/log/*'
        sh 'rm -rf data/coverage/*'
        sh 'rm -f data/*.xml'

        stage 'Install dependencies'
        sh 'composer install --no-interaction'

        stage 'Coding standard check'
        sh 'php vendor/squizlabs/php_codesniffer/scripts/phpcs --report=checkstyle --report-file=data/checkstyle.xml'

        stage 'PHPUnit'
        sh 'php vendor/phpunit/phpunit/phpunit --log-junit=data/unitreport.xml --coverage-html=data/coverage --coverage-clover=data/coverage/coverage.xml'

        stage 'Process artifacts'
        step([$class: 'CheckStylePublisher', pattern: '**/data/checkstyle.xml', unstableTotalAll: '0', usePreviousBuildAsReference: false])
        step([$class: 'JUnitResultArchiver', testResults: '**/data/unitreport.xml'])
        step([$class: 'CloverPublisher', cloverReportDir: 'data/coverage', cloverReportFileName: 'coverage.xml'])
    } catch(Exception err) {
        echo "Caught: ${err}"
        slackSend color: 'danger', message: "Failed: ${env.JOB_NAME} ${env.BUILD_DISPLAY_NAME}\n${env.BUILD_URL}console\nError: '${err}'"
        throw err
    }

    slackSend color: 'good', message: "Success: ${env.JOB_NAME} ${env.BUILD_DISPLAY_NAME}\n${env.BUILD_URL}"
}
