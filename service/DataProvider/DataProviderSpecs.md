Data Provider implementing Object Data Mapper
=============================================

A self optimizing DataProvider for an "Object Data Mapper" (ODM). 

!! Note: This is a theoretical document. Actual framework may not reflect the this document. At this time, all namespaces start with Fiji\.  

**What is Object Data Mapper**

The ODM is an interface that exposes a storage backend to a Object Data Model. So it maps SQL, No-SQL, Object Database, or Document Database, XML etc. to Objects. 
It's a layer above ORM. 

***Here is the pseudo code to generate an ObjectDataMapper instance.***

```
<?php
$Config = new Config\Service\ObjectDataMapper\DataProvider();
$DataProvider = new Service\ObjectDataMapper\DataProvider\ORM($Config);
$ODM = new Service\ObjectDataMapper($DataProvider);
$ModelUser = new Model\User()->addService($ODM);
```
Note: Actual framework code would use a factory to build and inject dependencies. 

***This is the hierarchy:***
```
Class Service\ObjectDataMapper\DataProvider\ORM implements Service\ObjectDataMapper\DataProvider {}

Class Service\ObjectDataMapper implements Service\DataMapper {}

Class Service\DataMapper\DomainObject implements Service\DomainObject {}

```

Now the ODM has an instance of the DataProvider that is specifically an ORM DataProvider. The ODM will call the methods through the interface Service\ObjectDataMapper\DataProvider to access the Service\ObjectDataMapper\DataProvider\ORM in a contractual way. 

Inside Service\ObjectDataMapper\DataProvider\ORM is the code that access the SQL db and performs the CRUD operations while exposing the standardized objects that Service\ObjectDataMapper uses. This objects would be of type Service\DomainObject. 

***Creating a DataProvider***

In order to implement a different storage interface or specific functionality you can create a DataProvider. The only requirement is to implement Service\ObjectDataMapper\DataProvider

Please see the current basic implementation in service\DataProvider\MySQL or full implementation in service\DataProvider\RedBean\RedBean.  
