// SPDX-License-Identifier: MIT
pragma solidity ^0.8.1;

import "@openzeppelin/contracts/token/ERC20/IERC20.sol";

contract CourseMarketplace {
    struct Course {
        uint id;
        string name;
        string description;
        uint priceInTokens;
        address payable instructor;
    }

    struct Transaction {
        uint id;
        string transactionType; // e.g., "Course Purchase"
        uint amount;
        uint timestamp;
    }

    struct Enrollment {
        uint courseId;
        uint timestamp;
        string studentName;
        string userAddress;
        string email;
        string contact;
    }

    uint public courseCount = 0;
    mapping(uint => Course) public courses;
    address public owner;
    IERC20 public token; // Use EcommerseToken (WTK)

    // Mapping to store transaction history by wallet address
    mapping(address => Transaction[]) public transactionHistory;

    // Mapping to store enrollments by wallet address
    mapping(address => Enrollment[]) public enrollments;

    event CourseCreated(
        uint id,
        string name,
        string description,
        uint priceInTokens,
        address payable instructor
    );

    event CoursePurchased(
        uint id,
        address payable student,
        uint priceInTokens
    );

    constructor(address _tokenAddress) {
        owner = msg.sender;
        token = IERC20(_tokenAddress);  // Initialize the token contract
    }

    modifier onlyOwner() {
        require(msg.sender == owner, "Only owner can perform this action");
        _;
    }

    function createCourse(
        string memory _name,
        string memory _description,
        uint _priceInTokens
    ) public onlyOwner {
        require(_priceInTokens > 0, "Price must be greater than zero");
        require(bytes(_name).length > 0, "Name cannot be empty");
        require(bytes(_description).length > 0, "Description cannot be empty");

        courseCount++;
        courses[courseCount] = Course(
            courseCount,
            _name,
            _description,
            _priceInTokens,
            payable(msg.sender)
        );

        emit CourseCreated(courseCount, _name, _description, _priceInTokens, payable(msg.sender));
    }

    function buyCourse(
        uint _id,
        string memory studentName,
        string memory userAddress,
        string memory email,
        string memory contact
    ) public {
        Course storage course = courses[_id];

        require(_id > 0 && _id <= courseCount, "Course does not exist");
        require(token.balanceOf(msg.sender) >= course.priceInTokens, "Not enough tokens to buy this course");
        require(token.allowance(msg.sender, address(this)) >= course.priceInTokens, "Token allowance too low");
        require(bytes(studentName).length > 0, "Student name cannot be empty");
        require(bytes(userAddress).length > 0, "User address cannot be empty");
        require(bytes(email).length > 0, "Email cannot be empty");
        require(bytes(contact).length > 0, "Contact cannot be empty");

        // Transfer tokens from buyer to instructor
        require(token.transferFrom(msg.sender, course.instructor, course.priceInTokens), "Token transfer failed");

        // Record the transaction for both buyer and instructor
        transactionHistory[msg.sender].push(Transaction({
            id: _id,
            transactionType: "Course Purchase",
            amount: course.priceInTokens,
            timestamp: block.timestamp
        }));
        transactionHistory[course.instructor].push(Transaction({
            id: _id,
            transactionType: "Course Sale",
            amount: course.priceInTokens,
            timestamp: block.timestamp
        }));

        // Save the enrollment details
        enrollments[msg.sender].push(Enrollment({
            courseId: _id,
            timestamp: block.timestamp,
            studentName: studentName,
            userAddress: userAddress,
            email: email,
            contact: contact
        }));

        emit CoursePurchased(_id, payable(msg.sender), course.priceInTokens);
    }

    function getTransactionHistory(address _wallet) public view returns (Transaction[] memory) {
        return transactionHistory[_wallet];
    }

    function getEnrollmentHistory(address _wallet) public view returns (Enrollment[] memory) {
        return enrollments[_wallet];
    }
}
