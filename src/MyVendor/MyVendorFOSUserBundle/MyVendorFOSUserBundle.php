<?php

namespace MyVendorFOSUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MyVendorFOSUserBundle extends Bundle
{
  public function getParent()
  {
      return 'FOSUserBundle';
  }
}
