# README #

This README would normally document whatever steps are necessary to get your application up and running.

### What is this repository for? ###

* A Magento 2 module to warm up the page cache
* Version 1.0.0

### How do I get set up? ###

**Add module to your composer.json file or clone repository**
 <pre>
 ```
{
    "repositories": [
        {
            "type": "vcs",
            "url":  "git@bitbucket.org:USERNAME/REPOSITORY.git"
        }
    ],
    "require": {
        "aizetech/pagecachewarm": "1.0.0"
    }
}
 ```
 </pre>
 ---
**Configuration**
* Magento Admin -> Stores -> Configuration -> Aizetech -> Page Cache Warm
* Add urls in rows below
* Save Configuration
 ---
**Dependencies**
* Module is dependent on Magento_PageCache module

 ---

### Who do I talk to? ###

* berkanduzgun@aizetech.nl