pragma solidity ^0.4.18;

contract Ownable {
    address public owner;

    event OwnershipTransferred(address indexed previousOwner, address indexed newOwner);

    /**
     * @dev The Ownable constructor sets the original `owner` of the contract to the sender
     * account.
     */
    function Ownable() public {
        owner = msg.sender;
    }

    /**
     * @dev Throws if called by any account other than the owner.
     */
    modifier onlyOwner() {
        require(msg.sender == owner);
        _;
    }

    /**
     * @dev Allows the current owner to transfer control of the contract to a newOwner.
     * @param newOwner The address to transfer ownership to.
     */
    function transferOwnership(address newOwner) public onlyOwner {
        require(newOwner != address(0));
        OwnershipTransferred(owner, newOwner);
        owner = newOwner;
    }

}

contract Grower is Ownable {
    mapping(address => string) public data;

    function Grower() public {
    }

    event Log(address indexed _address, string _data);

    function storeValue(address _address, string _data) public onlyOwner returns (bool) {
        require(_address != 0x0);
        data[_address] = _data;
        Log(_address, _data);
        return true;
    }
}

contract Lab is Ownable {
    mapping(address => string) public data;

    function Lab() public {
    }

    event Log(address indexed _address, string _data);

    function storeValue(address _address, string _data) public onlyOwner returns (bool) {
        require(_address != 0x0);
        data[_address] = _data;
        Log(_address, _data);
        return true;
    }
}

contract RawMaterial is Ownable {
    mapping(bytes32 => string) public data;

    function RawMaterial() public {
    }

    event Log(bytes32 indexed _uid, address indexed _address, string _data);

    function storeValue(bytes32 _uid, address _growerAddress, string _data) public onlyOwner returns (bool) {
        data[_uid] = _data;
        Log(_uid, _growerAddress, _data);
        return true;
    }
}

contract Expertise is Ownable {
    mapping(bytes32 => string) public data;

    function Expertise() public {
    }

    event Log(bytes32 indexed _uid, bytes32 indexed _growerUid, address indexed _labAddress, string _data);

    function storeValue(bytes32 _uid, bytes32 _growerUid, address _labAddress, string _data) public onlyOwner returns (bool) {
        data[_uid] = _data;
        Log(_uid, _growerUid, _labAddress, _data);
        return true;
    }
}


contract SupplyTracking is Ownable {

    address public growerContract;
    address public rawMaterialContract;
    address public labContract;
    address public expertiseContract;
    bytes32 public growerContractVersion;
    bytes32 public rawMaterialContractVersion;
    bytes32 public labContractVersion;
    bytes32 public expertiseContractVersion;


    mapping(bytes32 => string) public expertises;


    function SupplyTracking() public {
    }

    event ChangeGrowerContract(address indexed _address, bytes32 indexed _version);
    event ChangeRawMaterialContract(address indexed _address, bytes32 indexed _version);
    event ChangeLabContract(address indexed _address, bytes32 indexed _version);
    event ChangeExpertiseContract(address indexed _address, bytes32 indexed _version);

    //-------------------------------------------------------------------------------------------

    function setGrowerConract(address _newAddress, bytes32 _version) public onlyOwner returns (bool) {
        growerContract = _newAddress;
        growerContractVersion = _version;
        ChangeGrowerContract(_newAddress, _version);
        return true;
    }

    function setGrower(address _address, string _data) public onlyOwner returns (bool) {
        Grower c = Grower(growerContract);
        return c.storeValue(_address, _data);
    }

    //-------------------------------------------------------------------------------------------

    function setRawMaterialConract(address _newAddress, bytes32 _version) public onlyOwner returns (bool) {
        rawMaterialContract = _newAddress;
        rawMaterialContractVersion = _version;
        ChangeRawMaterialContract(_newAddress, _version);
        return true;
    }

    function setRawMaterial(bytes32 _uid, address _growerAddress, string _data) public onlyOwner returns (bool) {
        RawMaterial c = RawMaterial(rawMaterialContract);
        return c.storeValue(_uid, _growerAddress, _data);
    }

    //-------------------------------------------------------------------------------------------

    function setLabConract(address _newAddress, bytes32 _version) public onlyOwner returns (bool) {
        labContract = _newAddress;
        labContractVersion = _version;
        ChangeLabContract(_newAddress, _version);
        return true;
    }

    function setLab(address _address, string _data) public onlyOwner returns (bool) {
        Lab c = Lab(labContract);
        return c.storeValue(_address, _data);
    }

    //-------------------------------------------------------------------------------------------

    function setExpertisConract(address _newAddress, bytes32 _version) public onlyOwner returns (bool) {
        expertiseContract = _newAddress;
        expertiseContractVersion = _version;
        ChangeExpertiseContract(_newAddress, _version);
        return true;
    }

    function setExpertise(bytes32 _uid, bytes32 _growerUid, address _labAddress, string _data) public onlyOwner returns (bool) {
        Expertise c = Expertise(expertiseContract);
        return c.storeValue(_uid, _growerUid, _labAddress, _data);
    }

}