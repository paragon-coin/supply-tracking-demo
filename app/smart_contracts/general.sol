pragma solidity ^0.4.18;

contract SupplyTracking {

    address owner;

    mapping(address => string) public growers;
    mapping(bytes32 => string) public rawMaterials;

    mapping(address => string) public labs;
    mapping(bytes32 => string) public expertises;


    function SupplyTracking() public {
        ownable();
    }

    event Grower(address indexed _address, string _data);
    event RawMaterial(bytes32 indexed _uid, address indexed _growerAddress, string _data);
    event Lab(address indexed _address, string _data);
    event Expertise(bytes32 indexed _uid, bytes32 indexed _growerUid, address indexed _labAddress, string _data);

    function ownable() public { owner = msg.sender; }

    modifier onlyOwner() {
        require(msg.sender == owner);
        _;
    }

    function changeOwner(address newOwner) public onlyOwner returns(bool) {
        owner = newOwner;
    }

    function setGrower(address _address, string _data) public onlyOwner returns(bool) {
        growers[_address] = _data;
        Grower(_address, _data);
        return true;
    }

    function setRawMaterial(bytes32 _uid, address _growerAddress, string _data) public onlyOwner returns(bool) {
        rawMaterials[_uid] = _data;
        RawMaterial(_uid, _growerAddress, _data);
        return true;
    }

    function setLab(address _address, string _data) public onlyOwner returns(bool) {
        labs[_address] = _data;
        Lab(_address, _data);
        return true;
    }

    function setExpertise(bytes32 _uid, bytes32 _growerUid, address _labAddress, string _data) public onlyOwner returns(bool) {
        expertises[_uid] = _data;
        Expertise(_uid, _growerUid, _labAddress, _data);
        return true;
    }

}