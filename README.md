# Practical Module Development for PrestaShop 8

<a href="https://www.packtpub.com/product/practical-module-development-for-prestashop-17/9781837635962?utm_source=github&utm_medium=repository&utm_campaign=9781804613900"><img src="https://content.packt.com/B19447/cover_image_small.jpg" alt="" height="256px" align="right"></a>

This is the code repository for [Practical Module Development for PrestaShop 8](https://www.packtpub.com/product/practical-module-development-for-prestashop-17/9781837635962?utm_source=github&utm_medium=repository&utm_campaign=9781804613900), published by Packt.

**A practical guide to creating modern PrestaShop modules and 
bringing your online store to the next level**

## What is this book about?
After version 1.7, PrestaShop underwent a host of changes, including migration to a Symfony-based system from an outdated legacy code. This migration brought about significant changes for developers, from routine maintenance to module development. Practical Module Development for PrestaShop 8 is curated to help you explore the system architecture, including migrated and non-migrated controllers, with a concise data structure overview. You’ll understand how hooks enable module customization and optimize the CMS.

This book covers the following exciting features:
* Understand the structure of PrestaShop’s core
* Explore hooks and their functions
* Create a hello world module
* Build modules to display blocks in the front office with different styles
* Design a module to add fields to the category pages and manage them
* Fashion a blogging module with front and modern back-office controllers
* Fabricate payment and carrier modules to improve the user experience
* Customize a theme by creating a child theme

If you feel this book is for you, get your [copy](https://www.amazon.com/dp/183763596X) today!

<a href="https://www.packtpub.com/?utm_source=github&utm_medium=banner&utm_campaign=GitHubBanner"><img src="https://raw.githubusercontent.com/PacktPublishing/GitHub/master/GitHub.png" 
alt="https://www.packtpub.com/" border="5" /></a>

## Instructions and Navigations
All of the code is organized into folders. For example, Chapter10.

The code will look like the following:
```
$this->hookDispatcher->dispatchWithParameters('actionAfterCreate' . 
Container::camelize($form->getName()) . 'FormHandler', [
 'id' => $id,
 'form_data' => &$data,
 ]);
```

**Following is what you need for this book:**
If you are a junior or advanced PHP developer already using PrestaShop as a simple user willing to know more or to solve online sellers' problems by creating modules as a professional, this book is definitely for you. In order to learn from this book, you should have a basic knowledge of the Symfony framework. This book will be a really good help for the module developers expecting to move from the old legacy environment to the modern one. Other CMS developers can use that book as a tool to compare and move to PrestaShop.

With the following software and hardware list you can run all code files present in the book (Chapter 1-16).
### Software and Hardware List
| Chapter | Software required | OS required |
| -------- | ------------------------------------ | ----------------------------------- |
| 1-16 | Prestashop v1.7.x or 8.x | Windows, Mac OS X, and Linux (Any) |


We also provide a PDF file that has color images of the screenshots/diagrams used in this book. [Click here to download it](https://packt.link/9XyJg).

### Related products
* Joomla! 4 Masterclass [[Packt]](https://www.packtpub.com/product/joomla-4-masterclass/9781803238975?utm_source=github&utm_medium=repository&utm_campaign=9781803238975) [[Amazon]](https://www.amazon.com/dp/1803238976)

* Drupal 10 Development Cookbook - Third Edition [[Packt]](https://www.packtpub.com/product/drupal-10-development-cookbook-third-edition/9781803234960?utm_source=github&utm_medium=repository&utm_campaign=9781803234960) [[Amazon]](https://www.amazon.com/dp/1803234962)


## Get to Know the Author
**Louis AUTHIE**
is a freelance full-stack developer with over 25 years of experience in PHP. He graduated from the ENAC, Toulouse, France in 2011 as an engineer and from the CNAM Paris, France as an analyst programmer in 2017.
Since 2012, he’s developed and maintained modules and themes from various versions of PrestaShop. Becoming an associate for Label Naturel’s bedding e-shop in 2016 improved his awareness of online sellers’ challenges. In 2019, he started contributing to the PrestaShop open source project, first to the documentation, then to back-office migration and bug fixes. Louis co-founded Web-Helpers, a web agency, in 2020, and teaches professionals in PHP, WordPress, PrestaShop, and Symfony development as a consultant trainer.

