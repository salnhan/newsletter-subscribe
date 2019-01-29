# Newsletter Subscription

This extension offers double opt in newsletter subscription forms for the [TYPO3 CMS](http://typo3.org).

This extension depends on following extension:

1. [direct_mail](https://typo3.org/extensions/repository/view/direct_mail)
2. [tt_address](https://typo3.org/extensions/repository/view/tt_address)


## TypoScript Configuration
### Constants

Following TypoScript constants should be overridden in your project

     plugin.tx_form {
         newsletter {
             subscribe {
                 # sender address for the confirm email to subscriber
                 senderAddress = your_email@your_domain.de
                 senderName = Your Newsletter Team
     
                 # storagePid for email address of subscriber in backend
                 address {
                     storagePid = 515
                 }
     
                 # redirect page after subscribe
                 redirectPageUid = 517
             }

             userEmail {
                 confirm {
                     # success page after confirmation of subscription
                     successPageUid = 543
     
                     #  failure page after confirmation of subscription
                     failurePageUid = 544
                 }
     
                 deny {
                     # success page after deny of subscription
                     successPageUid = 505
     
                     # failure page after deny of subscription
                     denyFailurePageUid = 504
                 }
             }
     
             unsubscribe {
                 # unsubscribe page
                 pageUid = 516
     
                 # redirect page after unsubscribe with form
                 redirectPageUid = 518
             }
         }
     }