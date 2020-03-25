pipeline{
    agent any
    tools{
        ant 'Ant-Default'
    }
    stages{
        stage('Clean workspace'){
            steps{
                deleteDir()
            }
        }
        
        stage('Clone'){
            steps{
                git credentialsId: 'gitusername', url: 'https://gitlab.com/naveenverma023/php-ant-demo.git'
            }
        }
        
        stage('Cleanup'){
            steps{
                sh 'ant clean'
                
            }
        }

        stage('Prepare for build'){
            steps{
                sh 'ant prepare'
            }
        }

        stage('Execute Tests'){
            parallel{

                        stage('Check for syntax errors'){
                        steps{
                        sh 'ant Lint'
                        }
                    }
        
                        stage('Execute Unit tests'){
                        steps{
                        sh 'ant PHPUnit'
                        }
                    }
        
                        stage('Measure project size'){
                        steps{
                        sh 'ant phploc'
                        }
                    }
            }

        }

        stage('Execute quality checks'){
            parallel{

                        stage('Execute Checkstyle analysis'){
                        steps{
                        sh 'ant phpcs'
                        }
                    }

                        stage('Execute Code coverage'){
                        steps{
                        sh 'ant pdepend'
                        }
                    }

                        stage('Check code quality'){
                        steps{
                        sh 'ant phpmd'
                        }
                    }

                        stage('Check code duplications'){
                        steps{
                        sh 'ant phpcpd'
                        }
                    }

            }
        }
        
        
    }
}