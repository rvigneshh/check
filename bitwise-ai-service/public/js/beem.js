  //atehna specific
  function passThis(TheFunction) {
      console.log(TheFunction);
      switch (TheFunction) {
          case "calling_the_intro":
              calling_the_intro();
              break;
          default:
              //nothing happens
              break;
      }
  }