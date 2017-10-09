LibSieve
========

LibSieve is a library to manage and modify sieve (RFC5228) scripts. It contains a parser for the sieve language (including extensions) and a client for the managesieve protocol. It is written entirely in PHP 5.

This is a fork from https://github.com/ProtonMail/libsieve-php. It also includes script that that implements MANAGESIEVE from https://lists.sourceforge.net/mailman/listinfo/sieve-php-devel.

Install
=======

```
composer require loconox/libsieve
```


Changes from the RFC
====================

 - The `date` and the `currentdate` both allow for `zone` parameter any string to be passed.
   This allows the user to enter zone names like `Europe/Zurich` instead of `+0100`. 
    The reason we allow this is because offsets like `+0100` don't encode information about the 
    daylight saving time, which is often needed.