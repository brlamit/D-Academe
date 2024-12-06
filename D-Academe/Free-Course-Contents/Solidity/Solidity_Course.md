# Introduction to Solidity Programming

For: Beginners
Key Words: program, smart contracts
Last update by: Constantine Fomchenkov
Last updated time: February 21, 2023 5:58 AM
Topic: Fundementals

<aside>
💻 This is a Solidity onboarding guide designed for beginners. You'll learn all the basics of Solidity, which is the language used for writing smart contracts on the Ethereum blockchain.

</aside>

## Course outline:

- [Introduction to smart contracts and the Ethereum blockchain]
- [Solidity fundamentals]
    - [Remix IDE, Solidity versions, and documentation]
    - [Data types, variables, and Structs]
    - [Functions in Solidity]
    - [Arrays, Mappings, and require]
    - [Loops  and conditional statements statements]
    - [Enumerated lists and function modifiers]
    - [Events]
- [Creating and deploying simple smart contracts]
    - [Simple storage smart contract]
    - [Advanced storage contract]
    - [Rental cars manager]

## Introduction to smart contracts and the Ethereum platform

This section will explore the concept of *smart contracts,* what Ethereum is, and how they work on the Ethereum network.

### What is a smart contract?

Smart contracts are self-executing programs with the terms of the agreement between two parties being directly written into lines of code. The parties can be buyers, sellers, or users and a decentralized application (DApp). The code and the agreements contained therein exist on the blockchain network.

**But what does this mean, and how does it work?**

Well, smart contracts allow for the automation of online transactions. This means that the terms of the contract are automatically verified by the network, and if the terms are met, the contract is executed, and the transaction is completed. This helps to reduce the need for intermediaries and can increase the security, speed, and efficiency of transactions.

<aside>
💻 One of the great things about smart contracts is that they are transparent and immutable. This means that the transaction and contract are recorded on the blockchain, ensuring that everyone can see what happened and that the information can't be changed.

</aside>

Smart contracts can be used in various applications, including supply chain management, financial transactions, real estate, etc. They have the potential to revolutionize the way we do business and facilitate more secure and efficient transactions.

**What is a DApp?**

A DApp is a software application that runs on a decentralized network, typically using smart contracts to manage user interactions. DApps can be built on top of Ethereum and leverage its blockchain to create a secure and transparent platform for various applications.

**Here is a list of various applications that involve the use of smart contracts:**

- [Etherisc](https://etherisc.com/) — a decentralized insurance platform that allows users to create and sell insurance products using smart contracts.
- [OpenSea](https://opensea.io/) — a marketplace for buying and selling digital assets, including non-fungible tokens (NFTs).
- [Decentraland](https://decentraland.org/) — a virtual reality platform that allows users to create, experience, and monetize content and applications.
- [Uniswap](https://uniswap.org/) — a decentralized exchange (DEX) that enables users to trade Ethereum tokens using automated market makers.
- [Chainlink](https://chain.link/) — a decentralized oracle network that allows smart contracts to securely access off-chain data and resources.

### Ethereum and the EVM

Ethereum is a decentralized, open-source blockchain that enables the creation of smart contracts and [decentralized applications (DApps)](https://www.notion.so/Introduction-to-Solidity-Programming-8fb8e38d4f4449d3ad3b997aadb63567). It was created in 2015 by Vitalik Buterin, a Russian-Canadian programmer and co-founder of Bitcoin Magazine.

Ethereum is built on the same underlying technology as Bitcoin, but it expands upon it in several ways. While Bitcoin allows users to send and receive digital currency, Ethereum allows users to create and execute [smart contracts](https://www.notion.so/Introduction-to-Solidity-Programming-8fb8e38d4f4449d3ad3b997aadb63567). This code is executed by the Ethereum Virtual Machine (EVM), a runtime environment for smart contracts in Ethereum.

Ethereum is often referred to as a "world computer" because it allows anyone to run any program, as long as they have enough resources (in the form of Ether, the native cryptocurrency of Ethereum) and the program is well-written. This makes Ethereum a powerful platform for building various applications, from financial applications to games and social networks.

**What is the Ethereum Virtual Machine (EVM)**

The Ethereum Virtual Machine (EVM) is a runtime environment for smart contracts in Ethereum. It is a virtual machine that executes smart contracts and is responsible for processing and executing the code of decentralized applications (DApps) on the Ethereum platform.

The EVM is essentially a virtual computer that runs on the Ethereum network and can execute code similarly to a physical computer. ***It has its own instruction set, which runs code on the network.***

<aside>
💻 This last part is important because many blockchains are based on the EVM and its instruction set. This means that all EVM-compatible blockchains can run smart contracts like Ethereum, and you can interact with them in more or less the same way. This is an amazing feature to help adoption and allows developers to deploy applications on different networks without having to learn new and specific processes and programming languages.

</aside>

**List of the most popular EVM-compatible blockchains supporter by Chainstack:**

- Ethereum
- Polygon
- BNB Smart Chain
- Avalanche
- Fantom

[List of all of the blockchains supported by Chainstack— EVM and non-EVM.](https://chainstack.com/protocols/)

When a user creates a smart contract or a DApp on the Ethereum platform, the code is compiled into bytecode and uploaded to the Ethereum network. The EVM then executes the bytecode, which allows the smart contract or DApp to function as intended.

<aside>
💻 One of the key features of the EVM is that it is a [deterministic environment](https://blockchain.news/insight/determinism-the-philosophy-of-blockchain). This means that each operation always gives the same and pre-determined result; therefore, it is not possible for the network to access external data or resources, such as a user's personal data or external APIs, because this would introduce variables. We use decentralized oracles (Chainlink, for example) to solve this. A decentralized oracle is a piece of software that bridges the EVM and external data, allowing smart contracts and DApps to securely access off-chain data and resources.

</aside>

Overall, the EVM is an integral part of the Ethereum platform. It enables the execution of smart contracts and DApps and allows developers to build a wide range of decentralized applications on the Ethereum network.

## Solidity fundamentals

<aside>
💻 This section will cover basic Solidity programming, including an introduction to its documentation and the Remix IDE.

</aside>

### Remix IDE, Solidity versions, and documentation

### Remix IDE

Let’s start with Remix, an important tool for working with Solidity and smart contracts in a learning and testing environment. 

<aside>
💻 Check out the [Solidity by examples page](https://solidity-by-example.org/); it has an awesome collection of code snippets.

</aside>

Remix is an online Integrated Development Environment (IDE) for Solidity, the programming language for writing smart contracts on EVM-compatible blockchains. It allows developers to *write*, *test*, and *deploy Solidity code* directly from the browser. It also provides a debugger and other tools for testing and debugging smart contracts. Remix is a useful tool for quickly testing and iterating on Solidity code.

<aside>
💻 [Access the Remix IDE here!](https://remix.ethereum.org/)

</aside>

It might seem overwhelming at first but let’s break down the interface to make it easier:

The left-side navigation holds the main tools:

- [The file explorer](https://remix-ide.readthedocs.io/en/latest/file_explorer.html)
- [The Solidity compiler](https://remix-ide.readthedocs.io/en/latest/compile.html)
- [The tool to deploy contracts and interact with them](https://remix-ide.readthedocs.io/en/latest/run.html)

You can also add the [debugger tool](https://remix-ide.readthedocs.io/en/latest/debugger.html) from the settings.

 

![screely-1672411431729.png](Introduction%20to%20Solidity%20Programming%208fb8e38d4f4449d3ad3b997aadb63567/screely-1672411431729.png)

<aside>
💻 Watch this video to see how to use the Remix IDE to write and test smart contracts. This way you can actually test the code you will learn in the `Solidity fundamentals` section. 

- ****[Introduction to Remix for beginners](https://www.youtube.com/watch?v=WmeWbo7wzGI)****

</aside>

### Solidity— Where to start

The [Solidity documentation](https://docs.soliditylang.org/en/latest/) is an important resource for learning about the Solidity programming language and its features. It explains the language's syntax, semantics, standard library, and best practices for writing secure and efficient smart contracts.

Make sure to refer to the latest version available.

Talking about the version, Solidity is updated fairly often. We establish what version we want to use when writing the smart contract by including the **`pragma`** directive on top of the smart contract. 

This is an example of a smart contract written in Solidity:

```solidity
// SPDX-License-Identifier: MIT

pragma solidity ^0.8.0;

contract SimpleStorage {

    uint public favoriteNumber;

    function saveNumber(uint _Number) public {
        favoriteNumber = _Number;
    }

    function deleteNumber() public {
        favoriteNumber = 0;
    }

    function getNumber() public view returns(uint) {
        return favoriteNumber;
    }
}
```

 

Let’s break it down; this contract essentially allows a user to save, delete, and retrieve a number on the blockchain. 

**License identifier**

The Ethereum blockchain is an open-source environment, and it is important to know how we (or others) can use the code. Always include an [SPDX license identifier](https://docs.soliditylang.org/en/v0.8.17/layout-of-source-files.html?highlight=spdx#spdx-license-identifier) at the top as a comment. Comments in Solidity are written starting with **`//`** .

```solidity
// SPDX-License-Identifier: MIT
```

In this case, we assign the MIT license, which is less restrictive. Check out a [list of SPDX licenses and their restrictions](https://spdx.org/licenses/). If you do not want to specify a license or if the source code is not open-source, use the special value `UNLICENSED`.

**Pragma statement**

Next comes the `pragma` statement. The `pragma` [keyword is used to enable certain compiler features](https://docs.soliditylang.org/en/v0.8.17/layout-of-source-files.html?highlight=spdx#version-pragma) or checks. Essentially, we specify which version of the compiler to use.

```solidity
pragma solidity ^0.8.0;
```

In this case, we use the ^ symbol, which enables every compiler for version 8. When we go to compile the smart contract in Remix, we’ll need to choose at least version 0.8.0.

![screely-1672413957172.png](Introduction%20to%20Solidity%20Programming%208fb8e38d4f4449d3ad3b997aadb63567/screely-1672413957172.png)

**Declare a smart contract**

The `contract` keyword is what is used to declare a new smart contract.

```solidity
contract MyContract {
 // Some Solidity code
}
```

In this case, we just declared a smart contract named `MyContract`, and we can write its code between the brackets `{}`.

The rest of the code is the variables and functions that make the smart contract; we will learn more details about those in the next sections.

### Try to code in Remix

<aside>
💻 This basic course will show many practical examples of how the code works. Make sure to test the example in Remix yourself to feel it. 

It is also highly recommended to try to play with the examples and modify them to come up with different versions; this is the best way to learn and ensure you understand the concept!

</aside>

### Data types, variables, and Structs

[Data types](https://docs.soliditylang.org/en/v0.8.17/types.html) and variables are always a good place to start when learning a new programming language.

In programming, a variable is a container that holds a value. When you create a variable, you give it a name and a data type, which tells the computer what kind of value the variable can hold. For example, a variable with the data type `string` can hold a series of characters, like a word or a phrase. You can use variables to store and change values in your program, like numbers or names.

Variables in Solidity are declared with the following syntax:

```solidity
dataType visibility VariableName = something;
```

For example:

```solidity
uint public favoriteNumber = 1000;
```

In this case, we have a variable type unsigned integer, with public visibility (more on this later), named `favoriteNumber` , and a value of 1000 is assigned to it.

<aside>
💻 Note that every statement in Solidity must end with a `;`

</aside>

### Solidity Data types

Solidity has [several data types](https://docs.soliditylang.org/en/v0.8.17/types.html) that you can use in your code. Here is a list of some of the most common data types and what they are used for:

- **`bool`**: a boolean value, which can be either **`true`** or **`false`**.
- **`int`**: an integer value, which is a whole number.
- **`uint`**: an unsigned integer value, which is a whole number that is always positive.
- **`address`**: an Ethereum address, which is a 160-bit value representing the address of an Ethereum account.
- **`bytes`**: a dynamic-length byte array.
- **`string`**: a series of characters, like a word or phrase.
- **`enum`**: an enumeration, a type representing a set of named choices.
- **`struct`**: a way to group together related values.
- **`mapping`**: a way to store key-value pairs, like a dictionary.

<aside>
💻 Note that we can assign a specific amount of bits allowed to `int` and `unit` . Keywords `uint8` to `uint256` in steps of 8 (unsigned of 8 up to 256 bits) and `int8` to `int256`. `uint`and `int` are aliases for uint256 and int256, respectively. 

By assigning a different number of bits to **`int`** and **`uint`** variables, you can choose the range of values that the variable can represent and optimize the memory usage of your contract. However, it is important to choose an appropriate number of bits based on the range of values you expect the variable to hold, as using a larger number of bits than necessary can waste memory`,` and using a smaller number of bits than necessary can cause errors if the variable exceeds its range.

For example, a `uint16` can hold values from 0 to 2^16-1 or 0 to 65535.
A `uint256` can hold values from 0 to 2^256-1, which is a very large range of values. It is usually used to hold balances that are returned from the blockchain in [Wei](https://www.investopedia.com/terms/w/wei.asp), a very small measure leading to very large numbers.

</aside>

### State and local variables

In Solidity, [state variables](https://docs.soliditylang.org/en/v0.8.17/structure-of-a-contract.html?highlight=state%20variable#state-variables) are stored on the Ethereum blockchain and can be accessed and modified by any contract deployed on the blockchain, depending on their visibility. They are stored on the contract's storage and are persisted between function calls.

Local variables, on the other hand, are variables that are only accessible within the scope of a particular function or block of code. They are stored on the contract's stack and are only accessible within the function or block in which they are declared. Local variables are not persisted between function calls and are destroyed when the function or block ends.

### **Visibility**

We already mentioned visibility a few times, and it’s time to address it. 

In Solidity, the [visibility](https://docs.soliditylang.org/en/v0.8.17/cheatsheet.html?highlight=visibility#function-visibility-specifiers) keyword controls the accessibility of contract functions and state variables. It specifies whether a function or variable can be accessed or modified from outside the contract or whether it is only accessible within the contract.

Solidity has three visibility keywords: **`public`**, **`private`**, and **`internal`**.

- **`public`** functions and variables are accessible from outside the contract as well as from within the contract. They can be called or accessed by any contract or account with the contract's address.
- **`private`** functions and variables are only accessible from within the contract. They cannot be called or accessed from outside the contract and are only intended for use by the contract's internal logic.
- **`internal`** functions and variables are only accessible from within the contract and from contracts that inherit from the contract. They cannot be called or accessed from outside the contract or from contracts that do not inherit from the contract.

The visibility keyword is an important aspect of Solidity programming, as it allows you to control the accessibility of your contract's functions and variables and ensure that they are used correctly. It is also important for security, as it can help you prevent unauthorized access to sensitive data or functions.

The next smart contract holds a collection of how the data types would look declared and used in a function.

```solidity
// SPDX-License-Identifier: MIT

pragma solidity ^0.8.0;

contract Example {
  uint256 public balance; // public state variable
  uint256 private allowance; // private state variable

	int256 public number = -10; 
	uint256 public anotherNumber = 5;
	uint public standardNumber = 10;  
	bool public boolean = true;   
	string public aString = "A String";   
	address public favoriteAddress = 0x8f8e7012F8F974707A8F11C7cfFC5d45EfF5c2Ae;    
	bytes32 public someBytes = "Chainstack rocks!";

// This is a function 
  function localVariable(uint _num) public pure returns(uint) { 
        uint value = 10;  // local variable
                                           
			// local variables can be modified and interacted with inside the function
        value = value + _num + 5;

			// state variables can be modified by a function too
				number = value 
        return number;   
    }
}
```

### Structs

In Solidity, a [struct](https://docs.soliditylang.org/en/v0.8.17/types.html#structs) is a composite data type that allows you to group together related values and treat them as a single entity. It is similar to a class in other programming languages but has no associated behavior. Structs are useful for organizing data and making it easier to work with.

To declare a struct in Solidity, you use the **`struct`** keyword followed by the name of the struct and a list of fields inside curly braces. Fields are variables that make up the struct and can be of any data type. Here is an example of a struct that represents a user account:

```solidity
struct public Account {
  string name;
  uint age;
  bool active;
}
```

 

In this example, the **`Account`** struct has three fields: a **`string`** field called **`name`**, a **`uint`** field called **`age`**, and a **`bool`** field called **`active`**. You can create an instance of the struct by using the **`new`** keyword and assigning values to its fields.

Structs are a useful tool for organizing and storing data in Solidity contracts, and can help you write more readable and maintainable code.

### Summary

This section covered the different data types, variables, and structs available in Solidity, the programming language for writing smart contracts on the Ethereum blockchain.

We reviewed Solidity's various built-in data types, such as **`bool`**, **`int`**, **`uint`**, **`address`**, and **`bytes`**, and how to use them to declare variables and function arguments. You also learned about the **`struct`** data types, which allow you to define custom data types with named constants and grouped values, respectively.

You also learned about the different visibility keywords in Solidity, including **`public`**, **`private`**, and **`internal`**, which control the accessibility of functions and variables.

By understanding these concepts, you are now equipped with the knowledge to effectively use data types, variables, and structs in your Solidity contracts. You can use this knowledge to write clean, efficient, and well-structured code that is easy to understand and maintain.

### Functions in Solidity

A [function in Solidity](https://docs.soliditylang.org/en/v0.8.17/structure-of-a-contract.html?highlight=functions#functions) is a block of code that performs a specific task and may or may not return a value. Functions are used to organize and reuse code, making it easier to read and maintain.

A function is declared using the `function` keyword followed by its `name`, `parameters`, visibility, and whether or not it returns a value, specify the type if it returns something.

```solidity
function functionName(parameteres_here) visibility returns(data_type) {
	// some code in here
}
```

 For example:

```solidity
function addNumbers(uint a, uint b) public returns (uint) {
    return a + b;
}
```

This function, named `addNumbers`, takes two input parameters of type `uint` (unsigned integer) and returns a value of type `uint`. The `public` keyword means the function can be called from anywhere (inside and outside the contract). `returns (uint)` means that executing this function will give a `uint` as a result.

To call a function, you can use its name followed by the input values in parentheses. For example:

```solidity

uint result = addNumbers(3, 4);
```

This will call the `addNumbers` function with the input values 3 and 4, and assign the returned value to the variable "result."

Functions can also have more complex logic, including loops and conditional statements. They can also be marked as `private` or `internal` which means they can only be called from within the contract.

 

### View and pure functions

 

In Solidity, functions can be marked as either `view` or `pure`. These keywords are used to specify the function's behavior and are important for optimizing the contract's performance and security.

A `view` function is a read-only function that does not modify the contract's state. This means that the function cannot change the values of any contract variables or perform any actions that would alter the contract's data. View functions are useful for querying the contract's state, as they do not require any gas to execute.

A `pure` function is an even more restricted type of read-only function that does not modify the contract's state and does not depend on any external information. This means that the function can only use its input parameters and does not have access to any contract variables or external data. Pure functions are useful for performing calculations and returning a value, as they can be executed more efficiently than other types of functions.

Here is an example of a `view` function in Solidity:

```solidity
function getBalance(address account) public view returns (uint) {
    return account.balance;
}
```

This function, named `getBalance`, takes an input parameter of type `address` (the account's address) and returns the balance of that account as a value of type `uint`. The `view` keyword specifies that the function is read-only and does not modify the contract's state.

Here is an example of a `pure` function in Solidity:

```solidity
function addNumbers(uint a, uint b) public pure returns (uint) {
    return a + b;
}
```

This function, named `addNumbers`, takes two input parameters of type `uint` (unsigned integer) and returns their sum as a value of type `uint`. The `pure` keyword specifies that the function is read-only, does not modify the contract's state, and does not depend on any external information.

<aside>
💻 The Solidity compiler in Remix is very good at giving warnings and explaining errors; if you write a function that can be marked as `view` or `pure`, Remix will suggest you add the keyword. This is one of the reasons why Remix is so good for beginners.

</aside>

### Receive function

In Solidity, the **`receive()`** function is a special function that is automatically called whenever the contract receives a payment. It is a "fallback" function, which means that it is called as a last resort if no other function matches the incoming message or transaction.

The **`receive()`** function has the following characteristics:

- It must be declared as **`external payable`**, which means that it can be called from external accounts and receive payments in the form of Ether.
- It does not have a function signature, meaning it cannot be called directly. Instead, it is called implicitly whenever the contract receives a payment.
- It does not return a value, so it cannot be used to send a value back to the caller.

Here is an example of a basic **`receive()`** function:

```solidity
function receive() external payable {
    // Do something with the received payment
}
```

To use the **`receive()`** function, you would simply send a payment to the contract using your Ethereum wallet or a tool like Remix. The **`receive()`** function would then be called automatically, and the contract would execute the code inside the function.

Note that the **`receive()`** function is only called when the contract receives a direct payment, not when it is called as part of a function call. 

### Summary

In summary, functions in Solidity are blocks of code that perform a specific task and may or may not return a value. 

They are used to organize and reuse code, making it easier to read and maintain. Functions can be marked as either `view` or `pure` to specify their behavior and optimize the contract's performance and security. 

`View` functions are read-only and do not modify the contract's state, while `pure` functions are even more restricted and do not depend on any external information. 

It's important to use the appropriate keywords when declaring a function as `view` or `pure`, as this can affect the contract's performance and security. Functions are an essential part of any Solidity contract and are used to perform a wide variety of tasks.

### Arrays, Mappings, and require

This section will teach you about two important data types in Solidity: arrays and mappings.

`Arrays` allow you to store multiple values of the same data type in a single variable. They are useful for storing lists of items, such as a list of account balances or a list of product names.

On the other hand, Mappings allow you to create a `key-value` pair mapping, where you can store and retrieve values using a unique key. Mappings are useful for creating data structures that can easily access and modify, such as a database or dictionary.

By the end of this section, you will be familiar with the syntax and usage of arrays and mappings in Solidity, and you will be able to use them to create more complex and powerful smart contracts. Let's get started!

### Arrays

[Arrays in Solidity](https://docs.soliditylang.org/en/v0.8.17/types.html?highlight=arrays#arrays) are used to store multiple values of the same data type in a single variable. Imagine that you want to store a list of account balances in your smart contract. Instead of creating a separate variable for each balance, you can use an array to store all of the balances in a single place.

To create an array in Solidity, you need to specify the `data type` of values you want to store in the array and the size of the array. Here is an example of how to create an array of integers with a size of 10:

```solidity
uint[] balances;
```

You can also initialize the array with a set of values when you declare it. Here is an example of how to create an array of strings with a size of 5 and initialize it with some values:

```solidity
string[5] names = ["Sethu", "Ake", "Wuhzong", "Davide", "Priyank"];
```

You can use the value’s index to access the values in an array. Solidity Arrays are zero-indexed, meaning that the first value in the array has an index of 0, the second value has an index of 1, and so on. Here is an example of how to access the second value in the **`names`**
 array:

```solidity
names[1]; // returns "Ake"
```

### Fixed size arrays and dynamic size arrays

In Solidity, there are two types of arrays: fixed-size arrays and dynamic-size arrays.

Fixed-size arrays have a fixed number of elements that cannot be changed once the array is created. To create a fixed-size array, you need to specify the data type of the values that you want to store in the array and the size of the array. Here is an example of how to create a fixed-size array of integers with a size of 7:

```solidity
uint[7] addresses;
```

**Fixed-size** arrays are useful when you know exactly how many elements you need to store in the array, and you don't need to change the size of the array later on. They are also more efficient than dynamic size arrays because they use less gas when they are created and accessed.

On the other hand, **dynamic size** arrays do not have a fixed number of elements. They can grow or shrink as needed, and you can add or remove elements from the array anytime. To create a dynamic size array, you only need to specify the data type of values you want to store in the array. Here is an example of how to create a dynamic size array of integers:

```solidity
uint[] balances;
```

Dynamic size arrays are useful when you don't know how many elements you need to store in the array or change the array's size later. However, they are less efficient than fixed-size arrays because they use more gas when they are created and accessed.

### Nested arrays

In Solidity, you can also create nested arrays, which are arrays that contain other arrays as elements. Nested arrays can be useful for storing complex data structures, such as a list of lists or a multi-dimensional matrix.

To create a nested array in Solidity, you can simply include an array as an element in another array. Here is an example of how to create a nested array of integers:

```solidity
uint[][3] public array2D = [[1,2,3,4],[9,8,7,6]];
```

This creates a dynamic size array called `array2D`, which can contain 3 arrays of integers. You can initialize the `array2D` array with a set of nested arrays like this:

```solidity

uint[][3] public array2D = [[1, 2, 3], [4, 5, 6], [7, 8, 9]];
```

This creates an `array2D` array with three elements, each of which is an array of integers. You can access the elements of the nested arrays using multiple indices like this:

```solidity
array2D[1][2]; // returns 6
```

Nested arrays can hold a maximum of 15 nested arrays and must be the same type.

### Add elements to arrays

There are a few different ways to add elements to arrays in Solidity, depending on the type of array you are using and whether you want to add elements to the end of the array or insert them at a specific index.

You can use the push function to add elements to the end of a dynamic size array. The **`push`** function takes a single argument, which is the element you want to add to the array and adds the element to the end of the array. Here is an example of how to use the **`push`** function to add an element to a dynamic size array of integers; this function adds the value of the **`balance`** argument to the end of the **`balances`** array.

```solidity
uint[] balances;

function addBalance(uint balance) public {
    balances.push(balance);
}
```

If you use a fixed-size array, you cannot use the **`push`** function as the array size is fixed and cannot be changed because the memory is allocated to them. 

<aside>
💻 The `.length` method returns how many elements are in the array.

</aside>

Check how many elements are in the array:

```solidity
string[] public names = ["Sethu", "Ake", "Priyank"];

    function getLenght() public view returns (uint){
        return names.length;
    }

// getLength() returns 3
```

### Remove elements from arrays

There are a few different ways to remove elements from arrays in Solidity, depending on the type of array you are using and whether you want to remove elements from the end of the array or a specific index.

You can use the pop function to remove an element from the end of a dynamic size array. The **`pop`** function removes the last element from the array. 

Here is an example of how to use the **`pop`** function to remove the last element from a dynamic size array of integers; the function removes the last element from the **`balances`** array.

```solidity
uint[] public balances = [1,2,3];

    function removeBalance() public {
        balances.pop();
    }

// After calling the function, the array will look like this:
// uint[] public balances = [1,2];
```

The `pop` function is great for removing the last element of an array, but what if you want to remove an element from a specific index? In this case, it is a bit more complex. We can use the `delete` function to select a specific index, like the following:

```solidity
string[] public names = ["Sethu", "Ake", "Priyank"];

    function removeName() public {
        delete names[1];
    }
```

In this case, we are removing the `string` at the index 1 (Ake), but it’s not really removed because the `remove` function just ‘*zeroes*’ the value, so in this case, the array becomes like this:

```solidity
string[] public names = ["Sethu", "", "Priyank"];
```

So the element is not really deleted. To do it properly, we have to shift all of the elements around the index that we select to the left, so the selected element becomes the last; then, we can use the pop function to remove the last element, which in this case is the element that we wanted to remove 🙌

This code is a bit more complex and involves a loop and a require statement; we’ll talk more about those later; for now, the logic is as follow:

1. The function takes the index that you want to remove as a parameter.
2. The `require` statement checks if the index exists in the array; gives an error if not.
3. If **`_index`** is a valid index within the **`names`** array, then the function will start a loop that iterates over the elements in the **`names`** array starting from **`_index`**. For each loop iteration, the element at the current index **`i`** is set to the element at the index **`i + 1`**. This has the effect of shifting all the elements after the element at **`_index`** one position to the left, effectively overwriting the element at **`_index`**.
4. Finally, the function calls the **`pop()`** function on the **`names`** array, which removes the last element of the array. This ensures that the array maintains the correct length after removing the element at **`_index`**.

```solidity
string[] public names = ["Sethu", "Ake", "Priyank"];

function remove(uint _index) public {
        require(_index < names.length, "index out of bound");

        for (uint i = _index; i < names.length - 1; i++) {
            names[i] = names[i + 1];
        }
        names.pop();
    }

// After calling the function, the array will be:
//string[] public names = ["Sethu", "Priyank"];
```

In the next lessons, we’ll cover more about loops.

### Require statements

In Solidity, a **`require`** statement is used to check that a given condition is true and to halt the execution of the contract if the condition is not met. It is often used to validate inputs, ensure that certain conditions are satisfied before certain actions are performed, or enforce the invariants of a contract.

The basic structure of a require statement is as follows:

```solidity
require(condition, "Error message");
```

Here's an example of how you might use a **`require`** statement in a Solidity contract:

```solidity
//SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

contract Example {
  uint public value;

  function setValue(uint newValue) public {
    // Ensure that the new value is greater than the current value
    require(newValue > value, "New value must be greater than the current value");

    // Update the value if the condition is met
    value = newValue;
  }
}
```

In this example, the **`setValue`** function uses a **`require`** statement to ensure that the new value being passed to the function is greater than the current value of the contract's **`value`** variable. 

If the condition is not met (i.e., if **`newValue`** is not greater than **`value`**), the contract will stop executing, and the value will not be updated.

A **`require`** statement takes two arguments: the condition to be checked and an optional error message that will be emitted if the condition is not met. The error message can be helpful for debugging and for providing more context to the user if the condition is not met.

You can use require statements to, for example, only allow the owner of a smart contract to call a specific function

### Mappings

In Solidity, a mapping is a data structure that allows you to store and retrieve values using a unique key. Mappings are similar to hash tables or dictionaries in other programming languages.

Here is an example of how to declare a mapping in Solidity:

```solidity
// mapping of numbers associated with strings (colors)

mapping(uint => string) public colors; 
```

This declares a public mapping called `colors` that map keys of type `uint` to values of type `string`. The keys in a mapping can be any type, but they must be unique. The values in a mapping can be of any type.

To store a value in a mapping, you can use the assignment operator:

```solidity
// mapping[key] = value;

	function addColors() public {
        colors[1] = 'red';
        colors[2] = 'yellow';
        colors[3] = 'orange';
    }
```

This will store the value in the mapping under the `key`.

To retrieve a value from a mapping, you can use the same syntax as for storing a value:

```solidity
function getColor(uint _key) public view returns(string memory) {
        string memory color = colors[_key];
        return color;
    }
```

<aside>
❓ Why are the strings in the previous function declared as `memory`?

In Solidity, a string is a data type that represents a sequence of characters. Strings commonly store text data, such as names, addresses, and descriptions. To use a string in a Solidity program, it must be stored in memory.

Memory is a part of a computer's internal storage that stores data being used or processed by the CPU. It is a temporary storage area that is used to hold data while it is being worked on. In Solidity, memory is used to store data that is needed by a contract during its execution, such as function parameters and variables.

</aside>

Mappings are useful for storing and accessing large amounts of data in a contract. They are stored in a virtual machine's storage, which is a key-value database that is persisted on the blockchain. Because mappings are stored on-chain, they are accessible to all functions in the contract and can be modified by any function with the appropriate permissions.

### Constructor function

In Solidity, a `constructor` function is a special function executed when a contract is deployed to the blockchain. Constructor functions are used to initialize the state of a contract when it is first created.

A constructor function is defined using the **`constructor`** keyword and does not have a return type. It can accept parameters, which can be used to initialize the contract's state or configure its behavior.

<aside>
💻 This is a good time to introduce this because a constructor is a good way to automatically populate mappings when the smart contract is deployed.

</aside>

Here is an example of a simple constructor function to populate a mapping in Solidity:

```solidity
constructor() {
    colors[1] = 'red';
    colors[2] = 'yellow';
    colors[3] = 'orange';
  }
```

The mapping will already be populated once the contract is deployed.

A common use of a constructor is to assign an address as contract owner or admin; this is useful because your smart contract might have some functions that you want only the owner to be able to call, for example, functions to mint more tokens or to move funds out of the contract.

Here is an example:

```solidity
// SPDX-License-Identifier: MIT

pragma solidity ^0.8.0;

contract TestContract {

    address public owner;

    constructor() {
        owner = msg.sender;
    }
}
```

We have a public `address` data type variable named `owner`; the constructor function is defined using the **`constructor`** keyword and does not accept any parameters.

It is executed when the contract is deployed to the blockchain and sets the value of the **`owner`** variable to the address of the contract deployer, which is stored in the **`msg.sender`** variable.

<aside>
💻 The **`msg.sender`** variable is a built-in Solidity variable that holds the Ethereum address of the account that sent the current message (in this case, the address of the account that deployed the contract).

</aside>

So, in summary, this contract defines a public variable called **`owner`** that stores the Ethereum address of the account that deployed the contract. It does this by setting the value of **`owner`** to the value of **`msg.sender`** in the constructor function.

Now we can use the `owner` variable to access special functions using a `require` statement.

### Summary

In this section, we covered two important data structures in Solidity: **arrays** and **mappings**.

Arrays are a type of data structure that allows you to store a collection of items. They can be fixed-size, where the size is determined when the array is declared, or dynamic, where the size can be changed at runtime. You can access and modify the elements of an array using their index, which starts at 0. You can also iterate through an array using a for loop.

Mappings are another type of data structure in Solidity that allows you to store key-value pairs. The keys can be any type that is supported by Solidity, and the values can be of any type as well. Mappings are useful for storing data that needs to be looked up by a unique key, such as mapping user IDs to user names.

Both arrays and mappings are useful tools for storing and organizing data in your Solidity contracts. You can design more complex and powerful smart contracts by understanding how to use them effectively.

### Loops and conditional statements

This section will go over loops and conditional statements in Solidity! In this section, you will learn how to use these programming concepts to control the flow of your contract's execution.

Loops allow you to execute a block of code multiple times, either a fixed number of times or until a certain condition is met. The two main types of loops in Solidity are the `for loop` and the `while loop`.

Conditional statements, also known as control structures, allow you to execute a block of code only if a certain condition is met. The two main types of conditional statements in Solidity are the `if statement` and the `switch statement`.

Together, loops and conditional statements allow you to write complex and powerful smart contracts that can adapt to different situations and make decisions based on data. By the end of this section, you will have a solid understanding of how to use these programming concepts in your Solidity contracts.

### For loops

For loops are a type of Solidity loop that allows you to execute a block of code a certain number of times. They are useful for repeating a task multiple times or for iterating through an array.

Here is the basic structure of a for loop in Solidity:

```solidity
for (initialization; condition; increment) {
    // code to be executed
}
```

- The `initialization statement` is executed before the loop begins. It is usually used to declare and initialize a loop counter variable.
- The `condition` is tested at the beginning of each iteration. If the condition is true, the loop will continue to execute. If the condition is false, the loop will stop, and control will be passed to the next statement after the loop.
- The `increment statement` is executed at the end of each iteration. It is usually used to update the loop counter variable.

Here is an example of a function including a `for loop` that executes 10 times:

```
function count() public pure returns(uint) {
    
    uint number;

    for (uint i = 0; i <= 9; i++) {
    number+= 1 ;
    }

    return number;
 }
```

Inside the function, we first declare a local variable named `number`; then, the function has a for loop that runs from 0 to 9 (inclusive). Inside the loop, the value of **`number`** is incremented by 1 each time the loop iterates.

<aside>
💻 In this loop, we write `number+= 1;` to increment the `number` variable value. Note that using `+= 1` is the same as doing `number = number + 1;` .

</aside>

Finally, the function returns the value of **`number`**, which will be 10 after the loop has completed executing. This is because the loop runs 10 times (from 0 to 9), and each time it runs, **`number`** is incremented by 1.

So, essentially, this function counts from 0 to 9 and returns the final count, 10.

<aside>
💻 In this case, the loop started counting from 0, but you can start and end it with any value.

</aside>

For loops are a powerful tool for repeating tasks and iterating through arrays in Solidity. 

### Example

You can loop through an array and return the sum of its elements; you can use a for loop and a loop counter variable to iterate through the array and a variable to keep track of the sum.

Here is an example of how you can do this in a smart contract:

```solidity
// SPDX-License-Identifier: MIT

pragma solidity ^0.8.0;

contract TestContract {

    uint[] numbers = [1,6,9,5,2,15,24];

    function sumNumbers() public view returns (uint) {

        uint total = 0;

        for (uint i = 0; i < numbers.length; i++) {
            total += numbers[i];
        }

        return total;
    }

}
```

This smart contract defines an array of unsigned integers called **`numbers`** and initializes it with the values 1, 6, 9, 5, 2, 15, and 24.

It also defines a function called **`sumNumbers`**, which is a `public view` function (meaning it can be called by any external contract or user and does not modify the state of the smart contract) that takes no arguments and returns a **`uint`**.

Inside the function, a local variable **`total`** is declared and initialized to 0. This variable will be used to keep track of the sum of the elements in the **`numbers`** array.

The function has a for loop that runs from 0 to the length of the **`numbers`** array (exclusive), using the loop counter variable **`i`** to access each element of the array. Inside the loop, the value of **`total`** is incremented by the current element of the **`numbers`** array.

Finally, the function returns the value of **`total`**, which will be the sum of all the elements in the **`numbers`** array after the loop has completed executing.

So, this code defines an array of numbers and a function that calculates the sum of all the elements in that array. When the function is called, it will return the sum of 1, 6, 9, 5, 2, 15, and 24, which is 62.

### While loops

While loops are a type of loop in Solidity that allow you to execute a block of code repeatedly as long as a certain condition is true. They are useful for repeating a task an unknown number of times or until a certain condition is met.

Here is the basic structure of a while loop in Solidity:

```solidity
while (condition) {
    // code to be executed
}
```

The condition is tested at the beginning of each iteration. If the condition is true, the loop will continue to execute. If the condition is false, the loop will stop and control will be passed to the next statement after the loop.

<aside>
🚨 Be careful with `while loops` because they could potentially cause an infinite loop and a critical bug in your contract if not used properly!

</aside>

Here is an example of a function including a while `loop` that executes 10 times:

```solidity
function count() public pure returns(uint8) {    
        uint8 number;

        uint8 i = 1;
        while (i <= 10) {
            number +=1;
            i++;
        }
        return number;
     }
```

The function **`count`** is a pure function that takes no arguments and returns a **`uint8`** (8-bit unsigned integer). It declares a local **`uint8`** variable called a **`number`** and initializes it to 0.

The function has a while loop that runs as long as the loop counter variable **`i`** is less than or equal to 10. Inside the loop, the value of **`number`** is incremented by 1 each time the loop iterates. The loop counter variable **`i`** is also incremented by 1 at the end of each iteration.

<aside>
💻 In this case, we increment the `i` variable with `i++`. This is another way to add 1 to an existing number, just like saying `i = i+1;`  or `i +=1`.

The difference is that `i++` only works to add a value of 1, while `i+=` can add different increments; for example, `i+=4;` will increase the count by 4.

</aside>

Finally, the function returns the value of **`number`**, which will be 10 after the loop has completed executing. This is because the loop runs 10 times (as long as **`i`** is less than or equal to 10), and each time it runs, **`number`** is incremented by 1.

So, this function essentially counts from 1 to 10 and returns the final count of 10.

<aside>
💻 Note that in all those previous loops, we call the increment variable `i`, this is a common practice, and you will see many loops using this naming convention, but keep in mind that you can call the iteration variable how you prefer. Sometimes it might be useful to give it a more meaningful name so that people reading our code can easily understand what the code does.

</aside>

While loops are a powerful tool for repeating tasks and adapting to changing conditions in Solidity. 

### A use case for a while loop

One practical use case for a while loop in Solidity is implementing a function that waits for a certain condition to be met before continuing execution. For example, consider a contract that allows users to bid on an auction item. The contract might have a function that waits for the current bid to reach a certain minimum value before allowing the auction to proceed.

Here is an example of how this could be implemented in a function using a while loop:

```solidity
function waitForMinimumBid(uint minimumBid) public {
    
// Wait until the current bid is equal to or greater than the minimum bid
    while (bid < minimumBid) {
        // Do nothing (wait), or display a message
    }
    // Proceed with the auction
    // ...
}
```

In this example, the function **`waitForMinimumBid`** takes a **`uint`** argument called **`minimumBid`,** representing the minimum acceptable bid for the auction. It has a while loop that runs as long as the current **`bid`** is less than the **`minimumBid`**. Inside the loop, the function does nothing (it waits).

Once the current **`bid`** is equal to or greater than the **`minimumBid`**, the while loop will stop, and the function will proceed with the auction. This could involve executing other functions, such as incrementing the bid or closing the auction.

This is just one example of how a while loop can be used in a practical application in Solidity. There are many other possibilities, such as waiting for a certain amount of time to pass or for a user to confirm a transaction.

<aside>
❓ Why do I see names written like this **`waitForMinimumBid`** where the first letter is lowercase, and each word's initial is capitalized?

This is called [**camel case**](https://en.wikipedia.org/wiki/Camel_case), and it is a popular confection for naming variables and functions in many programming languages.

![Untitled](Introduction%20to%20Solidity%20Programming%208fb8e38d4f4449d3ad3b997aadb63567/Untitled.png)

</aside>

### Conditional statements

Conditional statements, also known as control structures, are programming constructs that allow you to execute a block of code only if a certain condition is met. They are useful for making decisions based on data and adapting to different situations.

### If statement

The `if` statement is used to execute a code block only if a certain condition is true. It has the following basic structure:

```solidity
if (condition) {
    // code to be executed if the condition is true
}
```

You can also include an optional **`else`** clause to execute a different block of code if the condition is false:

```solidity
if (condition) {
    // code to be executed if the condition is true
} else {
    // code to be executed if the condition is false
}
```

Here is an example of an if statement that checks whether a number is positive or negative and updates a counter to see how many numbers are positive or negative:

```solidity
uint public positiveCounter = 0;
uint public negativeCounter = 0;

function countPositiveAndNegative(int number) public {
    if (number > 0) {
        positiveCounter++;
    } else {
        negativeCounter++;
    }
}
```

In this example, the function **`countPositiveAndNegative`** takes an **`int`** (signed integer) argument called **`number`** and updates the global counters **`positiveCounter`** and **`negativeCounter`** based on whether **`number`** is positive or negative.

The function has an if statement that checks whether **`number`** is greater than 0. If it is, the **`positiveCounter`** is incremented by 1. If **`number`** is not greater than 0 (i.e., it is negative or 0), the **`negativeCounter`** is incremented by 1.

This function can be called multiple times with different numbers as arguments, and the counters will be updated accordingly. For example, if you call **`countPositiveAndNegative(5)`** and **`countPositiveAndNegative(-3)`**, **`positiveCounter`** will be 1, and **`negativeCounter`** will be 1.

### Enumerated lists and function modifiers

This section will cover [enumerated lists](https://docs.soliditylang.org/en/v0.8.17/structure-of-a-contract.html?highlight=enum#enum-types) and [function modifiers](https://docs.soliditylang.org/en/v0.8.17/structure-of-a-contract.html?highlight=modifiers#function-modifiers) in Solidity! This section will teach you how to define and use enumerated lists and function modifiers in your smart contracts.

An `enumerated list`, also known as an `enum`, is a type of data structure that allows you to define a set of named values, called elements or members, that can be represented by a set of integers. Enums are useful for defining a fixed set of values, such as the days of the week or the suits in a deck of cards.

A `function modifier` is a special type of function that is used to modify the behavior of other functions. Modifiers are used to enforce conditions or constraints on the execution of a function, such as requiring a certain level of authorization or checking for certain input values.

By the end of this section, you will be able to define and use enumerated lists and function modifiers in your Solidity code to improve the readability and flexibility of your smart contracts. 

### Enumerated lists

In Solidity, an enumerated list is a data type that allows you to define a set of named values. This can be useful when you want to specify a fixed set of options, such as the days of the week or the different states of an order.

To create an enumerated list in Solidity, you can use the **`enum`** keyword, followed by the name of the enumerated list and a list of its values in curly braces. 

Here's an example of how you might define an enumerated list for different order statuses:

```solidity
enum OrderStatus {
  Pending,
  Shipped,
  Delivered
}
```

Once you have defined an enumerated list, you can use its values in your code just like any other variable. 

For example, you could use the enumerated list above to store the status of an order after a specific operation happened:

```solidity
function placeOrder() public {
    // Set the initial status of the order to "Pending"
    status = OrderStatus.Pending;
  }
```

Here's an example of how you might use an enumerated list in a Solidity contract to keep track of an order:

```solidity
//SPDX-License-Identifier: MIT

pragma solidity ^0.8.0;

contract Order {

    enum OrderStatus {
    Pending,
    Shipped,
    Delivered
    }

  OrderStatus public status;

  function placeOrder() public {
    // Set the initial status of the order to "Pending"
    status = OrderStatus.Pending;
  }

  function shipOrder() public {
    // Only allow the order to be shipped if its status is "Pending"
    require(status == OrderStatus.Pending, "Order must be pending to be shipped");

    // Update the status of the order to "Shipped"
    status = OrderStatus.Shipped;
  }

  function deliverOrder() public {
    // Only allow the order to be delivered if its status is "Shipped"
    require(status == OrderStatus.Shipped, "Order must be shipped to be delivered");

    // Update the status of the order to "Delivered"
    status = OrderStatus.Delivered;
  }
}
```

In this example, the **`OrderStatus`** enumerated list is used to define the possible states of an order:

- **`Pending`**
- **`Shipped`**
- **`Delivered`**

The **`status`** variable of the **`Order`** contract is of type **`OrderStatus`**, so it can only be assigned one of these values.

The functions in the contract use the enumerated list values to check the order's current status and ensure that certain actions are only allowed in certain states. 

For example, the **`shipOrder`** function uses an **`require`** statement to check that the status is **`Pending`** before updating it to **`Shipped`**, and the **`deliverOrder`** function does the same thing with the **`Shipped`** status.

Using an enumerated list helps ensure that the contract executes correctly and that the order is processed in the correct sequence. It also makes the code easier to read and understand since the meaning of the different values is clearly defined.

### Function modifiers

In Solidity, function modifiers are a way to add additional behavior to a function. They allow you to specify conditions that must be met for the function to execute or perform certain actions before or after the function is called.

To define a function modifier, you can use the **`modifier`** keyword, followed by the name of the modifier and a set of curly braces that contain the code to be executed. 

Here is what the basic syntax for a modifier looks like:

```solidity
modifier iAmAModifier{
    require(condition, "Error message");
    _;
  }
```

The **`_;`** at the end of the modifier's code block indicates that the modified function should be executed after the modifier's code has run. This allows you to specify the conditions that must be met before the function is called and to perform any additional actions before or after the function is executed.

Here's an example of a simple function modifier that checks that the caller of the function is the contract owner:

```solidity
//SPDX-License-Identifier: MIT

pragma solidity ^0.8.0;

contract Example {

  address public owner;
  uint public price;

  constructor() {
    owner = msg.sender;
    price = 0.2 ether;
  }

  modifier onlyOwner {
    require(msg.sender == owner, "Only the owner can call this function");
    _;
  }

  function setPrice(uint _newPrice) public onlyOwner {
    price = _newPrice ;
  }

}
```

In this example, the initialize the `price` state variable at 0.2 Ethers during deployment. 

The **`onlyOwner`** modifier uses a **`require`** statement to check that the caller of the **`setPrice`** function (i.e. **`msg.sender`**) is the same as the contract's **`owner`** variable. If the condition is not met, the contract will stop executing and the function will not be called.

<aside>
💻 In the latest example, we declared a value of `0.2 ether` in the `price` variable. This is very useful to make the code more readable, but keep in mind that Solidity does not work with decimal numbers, and all of the input taken and results returned will be in [Wei](https://www.investopedia.com/terms/w/wei.asp).

0.2 Ethers = 200000000000000000 Wei

You can use a [Wei converter](https://eth-converter.com/) or JavaScript libraries to convert from code.

</aside>

Function modifiers can be very useful for adding additional security and functionality to your contracts. They can help ensure that certain functions are only called under certain conditions and make it easier to manage complex logic in your contract code.

### Events

In Solidity, events are a way to send information to applications listening from outside the smart contract. They allow you to broadcast information about what has happened in your contract to other contracts or to external applications, such as a web interface or mobile app.

To define an event in Solidity, you can use the **`event`** keyword, followed by the name of the event and a list of its parameters in parentheses. 

Here's an example of how you might define an event in a contract:

```solidity
event ValueChanged(uint oldValue, uint newValue);
```

In this example, the **`ValueChanged`** event is defined with two parameters:

- **`oldValue`**
- **`newValue`**

Here's an example of how you might define an event in a contract:

```solidity
//SPDX-License-Identifier: MIT

pragma solidity ^0.8.0;

contract Example {

  event ValueChanged(uint oldValue, uint newValue);

  uint public value;

  function setValue(uint newValue) public {

    // Emit the ValueChanged event with the old and new values
    emit ValueChanged(value, newValue);

    // Update the value
    value = newValue;
  }
}
```

When the **`setValue`** function is called and the value is updated, the **`ValueChanged`** event is emitted with the old and new values as arguments.

When a function is executed using the `emit` keyword, the event's name, and the parameters, emit events.

To listen for events from a smart contract, you can use libraries like [web3.js](https://web3js.readthedocs.io/en/v3.0.0-rc.5/getting-started.html). 

<aside>
💻 Evens emit logs from smart contracts, and you can retrieve the logs by using the `eth_getLogs` method from the Ethereum API. 

- [eth_getLogs](https://docs.chainstack.com/api/ethereum/eth_getlogs) in the Chainstack docs. We’ll learn how to use these methods in a different course.

</aside>

Events are very common in smart contracts because they allow external apps to listen to them, get data, and react to them.

## Creating and deploying simple smart contracts

If you went through the Solidity fundamentals section, you already have all of the tools to create smart contracts!

In this section, we’ll practice with two examples:

- Simple storage smart contracts
- A simple rental car manager smart contract

<aside>
💻 Test these smart contracts in Remix, and try to come up with variations or new features.

</aside>

### Simple storage smart contract

A simple storage smart contract is a type of smart contract that is used to store and retrieve data on the blockchain. It typically consists of a single contract that defines a few basic functions for storing and retrieving data and a storage area where the data is stored.

**Example of a basic storage smart contract:**

```solidity
// SPDX-License-Identifier: MIT

pragma solidity >=0.6.0 <0.9.0;

//this smart contract allows us to store a retrieve a number

contract SimpleStorage{    

// A state variable
  uint256 userNumber;                   
	
// Function to store a number in a variable
  function store(uint256 _number) public {            
      userNumber =  _number;          
  }
    
// Function to retrieve the number from the variable
    function retrieve() public view returns (uint256) {         
        return userNumber;                          
    }
                                       
}
```

**Explanation:**

The first line of the code (**`// SPDX-License-Identifier: MIT`**) is a comment that specifies the license for the contract. The **`SPDX-License-Identifier`** is a standardized way to specify the license for a piece of software. In this case, the contract is licensed under the MIT License.

The second line (**`pragma solidity >=0.6.0 <0.9.0;`**) is a version pragma that specifies the version of Solidity that the contract is compatible with. The pragma specifies that the contract is compatible with Solidity versions 0.6.0 and above but not with versions 0.9.0 and above.

The **`SimpleStorage`** contract has two functions: 

- **`store`**
- **`retrieve`**

The **`store`** function takes a single argument, **`_number`**, which is a variable of type **`uint256`**. The **`uint256`** type represents an unsigned integer value of 256 bits in size. The function stores the value of **`_number`** in the **`userNumber`** variable, which is also of type **`uint256`**.

The **`retrieve`** function does not take any arguments and returns a value of type **`uint256`**. It simply returns the value of the **`userNumber`** variable.

Together, these functions allow you to store and retrieve a number using the **`SimpleStorage`** contract. When you deploy the contract to the blockchain and call the **`store`** function with a number, the number will be stored in the **`userNumber`** variable. 

You can then call the **`retrieve`** function to retrieve the stored number.

<aside>
💻 Note the name of the parameter starting with `_`, **`_number`.**

This is a convention to name parameter variables, it is not mandatory, and the code will work anyway, but it’s a good way to keep the code standard when working with different developers.

</aside>

### More advanced storage smart contract

This next example follows the same storage principle but it allows to store data in a struct. In this case a struct of people where you can store name and phone number.

```solidity
// SPDX-License-Identifier: MIT

pragma solidity >=0.6.0 <0.9.0;

//this smart contract allows us to store names associated with numbers

contract Storage{                           

    struct People{ 
        string name;                       
        uint256 number;        
    }

// this array will hold the structs creaded by the contract
    People[] public people;                                             

// this mapping allows to associate a name to a number so we can search by name
    mapping(string => uint256) public nameToNumber;                     

 // adds a struct People to the array
// adds a name and number to the mapping
    function addPerson(string memory _name, uint256 _number) public{
        people.push(People(_name, _number));                           
        nameToNumber[_name] = _number;                                  
    }   
    
}
``` 

The contract has a single function, `addPerson`, which takes two arguments: a string representing a name and a uint256 representing a number.

When this function is called, it adds a new `People` struct to the `people` array, with the name and number provided as arguments. It also adds an entry to the `nameToNumber` mapping, with the name as the key and the number as the value. 

This allows users to look up a person's number by their name using the mapping.

```solidity
struct People{ 
        string name;                       
        uint256 number;        
    }
```

Defines a new struct type called `People`. In this case, the `People` struct has two fields: a string called `name` and a uint256 called `number`.

```solidity
**People[] public people;**
```

Defines an array of `People` structs called `people`. The `public` keyword means that the array can be accessed from outside the contract.

```solidity
**mapping(string => uint256) public nameToNumber;**
```

Defines a mapping called `nameToNumber` that maps strings (representing names) to uint256 values (representing numbers). The `public` keyword means that the mapping can be accessed from outside the contract.

```solidity
function addPerson(string memory _name, uint256 _number) public{
        people.push(People(_name, _number));                           
        nameToNumber[_name] = _number;                                  
    }   
```

The function takes two arguments: a string called *`name`* and a uint256 called *`_*number`. These arguments represent a person's name and number, respectively.

The function performs the following actions:

1. It adds a new `People` struct to the `people` array using the `push` function. The struct is created using the `_name` and `_number` arguments passed to the function.
2. It adds a new entry to the `nameToNumber` mapping using the `_name` argument as the key and the `_number` argument as the value.

This function provides a way for users to add new people (name and number) to the contract's storage structures.

### Rental car manager

Now it’s time to put together everything you have learned in a new, more complex smart contract. You will use almost all of the tools you have learned so far.

The smart contract allows a customer to rent a Lambo by sending a payment of 2 ETH to the contract. The customer can then use the Lamb until they are ready to return it, at which point they can call the **`returnLambo()`** function to make the Lamborghini available for rent again.

```solidity
// SPDX-License-Identifier: MIT

pragma solidity >=0.6.0 <0.9.0;

contract RentLambo {

    
    constructor() {                                                                     
        contractOwner = payable(msg.sender);                                            
        lambo = LamboConditions.Available;                                              
    }

    address payable public contractOwner;                                               

    enum LamboConditions { Available , Rented }    

// This is a 'LamboConditions' data type named 'lambo'.
// It's used to store the condition of the Lambo.                                     
    LamboConditions lambo;

    event Rented(address _customer, uint _amount);                                      

    modifier statusError {
        require(lambo == LamboConditions.Available , "The Lambo is already rented");    
        _;                                                                              
    }

    modifier paymentError (uint _value) {
        require(msg.value >= _value, "Not enough ETH");                                 
        _;                                                                              
    }

    function rentLambo() payable public statusError paymentError(2 ether){              
        contractOwner.transfer(msg.value);                                              
        lambo = LamboConditions.Rented;
        emit Rented(msg.sender, msg.value);                                             
    }

    function returnLambo() public {                                                                    
        lambo = LamboConditions.Available;
    }

    function getCondition() public view returns(LamboConditions){                      
        return lambo;
    }

    receive() external payable statusError paymentError(2 ether){                       
        contractOwner.transfer(msg.value);                                            
        lambo = LamboConditions.Rented;
        emit Rented(msg.sender, msg.value);
    }
}
```

The contract has the following features:

- A constructor function that runs when the contract is deployed. This function sets the contract owner to the account that deployed the contract and sets the initial state of the Lambo to "Available".
- An enumeration (**`enum`**) that represents the possible states of the Lambo: "Available" or "Rented".
- An **`event`** called **`Rented`** is emitted every time the Lamborghini is rented out. This event includes the address of the customer and the amount they paid to rent the Lamborghini.
- Two **`modifier`** functions: **`statusError`** and **`paymentError`**. These functions are used to check the current state of the Lambo and the payment amount received, respectively. If either check fails, the function that calls the modifier will not execute.
- The **`rentLambo()`** function allows a customer to rent the Lamborghini by sending a payment of 2 ETH to the contract. This function is decorated with the **`statusError`** and **`paymentError`** modifiers, meaning it will only execute if the Lamborghini is available. The customer has sent the correct amount of ETH. If the function executes successfully, it transfers the payment to the contract owner and updates the state of the Lamborghini to "Rented".
- The **`returnLambo()`** function allows a customer to return the Lambo, setting its state back to "Available".
- The **`getCondition()`** function is a view function that returns the current state of the Lambo.
- The **`receive()`** function is a special function that is automatically called whenever the contract receives a payment. This function is decorated with the **`statusError`** and **`paymentError`** modifiers, meaning it will only execute if the Lambo is available and the payment is for the correct amount (2 ETH). If the function executes successfully, it transfers the payment to the contract owner and updates the state of the Lambo to "Rented".

<aside>
💻 Of course, this smart contract is only a basic example, and many improvements are possible. 
Try to modify this smart contract to add improvements and new features to practice your new Solidity skills!

</aside>