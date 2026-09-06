-- Create Orders Table for Men Salon Management System
-- This will store all customer cart/orders information

CREATE TABLE IF NOT EXISTS `tblorders` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderNumber` varchar(50) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(200) NOT NULL,
  `ProductPrice` decimal(10,2) NOT NULL,
  `ProductImage` varchar(255) DEFAULT NULL,
  `Quantity` int(11) NOT NULL DEFAULT '1',
  `TotalAmount` decimal(10,2) NOT NULL,
  `CustomerName` varchar(200) DEFAULT NULL,
  `CustomerEmail` varchar(200) DEFAULT NULL,
  `CustomerPhone` varchar(20) DEFAULT NULL,
  `OrderStatus` varchar(50) DEFAULT 'Pending',
  `OrderDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create Cart Table (for temporary storage before checkout)
CREATE TABLE IF NOT EXISTS `tblcart` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` varchar(100) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(200) NOT NULL,
  `ProductPrice` decimal(10,2) NOT NULL,
  `ProductImage` varchar(255) DEFAULT NULL,
  `Quantity` int(11) NOT NULL DEFAULT '1',
  `AddedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
