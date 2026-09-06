-- Complete Database Setup for Men Salon Management System
-- Run this SQL in phpMyAdmin to setup complete product and cart functionality

-- Use the database
USE msmsdb;

-- Create Products Table (if not exists)
CREATE TABLE IF NOT EXISTS `tblproducts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductName` varchar(200) NOT NULL,
  `ProductDescription` text NOT NULL,
  `ProductPrice` decimal(10,2) NOT NULL,
  `ProductImage` varchar(255) DEFAULT NULL,
  `Category` varchar(100) DEFAULT NULL,
  `Stock` int(11) DEFAULT '0',
  `Status` int(11) DEFAULT '1',
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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

-- Create Orders Table (for completed orders)
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

-- Insert sample products (30+ Products)
INSERT INTO `tblproducts` (`ProductName`, `ProductDescription`, `ProductPrice`, `ProductImage`, `Category`, `Stock`, `Status`) VALUES
('Premium Hair Wax', 'Strong hold hair wax for perfect styling. Provides long-lasting hold with natural shine.', 450.00, 'post-img-1.jpg', 'Hair Care', 50, 1),
('Beard Oil', 'Nourishing beard oil with essential oils. Keeps your beard soft, shiny, and healthy.', 550.00, 'post-img-2.jpg', 'Beard Care', 45, 1),
('Hair Styling Gel', 'Professional strength gel for all-day hold. Perfect for any hairstyle.', 380.00, 'left-image.jpg', 'Hair Care', 60, 1),
('Shaving Cream', 'Rich lathering shaving cream for smooth shave. Moisturizes and protects skin.', 320.00, 'about-img.jpg', 'Shaving', 70, 1),
('Hair Spray', 'Flexible hold hairspray that keeps your style in place without stiffness.', 420.00, 'related-post-1.jpg', 'Hair Care', 55, 1),
('Beard Balm', 'Conditioning beard balm with shea butter. Tames and shapes your beard.', 580.00, 'service-single.jpg', 'Beard Care', 40, 1),
('Hair Pomade', 'Classic pomade for slick, shiny hairstyles. Medium hold with high shine.', 490.00, 'post-img-1.jpg', 'Hair Care', 48, 1),
('Face Scrub', 'Exfoliating face scrub for men. Removes dead skin and refreshes face.', 350.00, 'post-img-2.jpg', 'Skin Care', 65, 1),
('Moisturizing Lotion', 'Lightweight daily moisturizer for men. Hydrates without greasiness.', 480.00, 'left-image.jpg', 'Skin Care', 52, 1),
('Matte Clay', 'Ultra-strong hold matte clay for textured styles. Natural finish without shine.', 520.00, 'about-img.jpg', 'Hair Care', 42, 1),
('Beard Wash', 'Gentle daily cleanser for beards. Removes dirt while keeping natural oils.', 420.00, 'related-post-1.jpg', 'Beard Care', 55, 1),
('Aftershave Balm', 'Soothing aftershave balm with aloe vera. Reduces irritation and redness.', 380.00, 'service-single.jpg', 'Shaving', 60, 1),
('Hair Serum', 'Nourishing hair serum with argan oil. Adds shine and reduces frizz.', 590.00, 'post-img-1.jpg', 'Hair Care', 38, 1),
('Beard Comb', 'Handcrafted wooden beard comb. Anti-static and gentle on beard hair.', 250.00, 'post-img-2.jpg', 'Beard Care', 80, 1),
('Face Wash', 'Deep cleansing face wash for men. Controls oil and prevents acne.', 320.00, 'left-image.jpg', 'Skin Care', 75, 1),
('Hair Gel Extreme Hold', 'Maximum hold gel for extreme hairstyles. All-day lasting formula.', 420.00, 'related-post-1.jpg', 'Hair Care', 50, 1),
('Pre-Shave Oil', 'Prepare your beard for smooth shaving. Softens hair and protects skin.', 380.00, 'service-single.jpg', 'Shaving', 65, 1),
('Hair Mask', 'Deep conditioning treatment for damaged hair. Restores health and shine.', 650.00, 'post-img-1.jpg', 'Hair Care', 35, 1),
('Beard Conditioner', 'Softening conditioner for beard hair. Makes beard more manageable.', 520.00, 'post-img-2.jpg', 'Beard Care', 45, 1),
('Sunscreen Lotion', 'Protect your skin from harmful UV rays. SPF 50 protection.', 450.00, 'left-image.jpg', 'Skin Care', 70, 1),
('Hair Tonic', 'Strengthening hair tonic for healthy growth. Reduces hair fall.', 580.00, 'about-img.jpg', 'Hair Care', 40, 1),
('Beard Growth Oil', 'Stimulating beard growth oil with biotin. Promotes fuller beard growth.', 680.00, 'related-post-1.jpg', 'Beard Care', 38, 1),
('Body Wash', 'Refreshing body wash for men. Deep cleansing with mint and eucalyptus.', 420.00, 'service-single.jpg', 'Skin Care', 60, 1),
('Hair Powder', 'Volumizing hair powder for textured styling. Matte finish with strong grip.', 480.00, 'post-img-1.jpg', 'Hair Care', 42, 1),
('Beard Dye', 'Natural beard color enhancement. Easy application with long-lasting results.', 550.00, 'post-img-2.jpg', 'Beard Care', 30, 1),
('Lip Balm', 'Moisturizing lip balm for men. Protects from dryness and cracking.', 180.00, 'left-image.jpg', 'Skin Care', 90, 1),
('Hair Fiber', 'Instant hair thickening fibers. Natural looking volume and coverage.', 750.00, 'about-img.jpg', 'Hair Care', 25, 1),
('Beard Shampoo', 'Specialized shampoo for beards. Cleans without stripping natural oils.', 480.00, 'related-post-1.jpg', 'Beard Care', 50, 1),
('Night Cream', 'Rejuvenating night cream for men. Repairs skin while you sleep.', 620.00, 'service-single.jpg', 'Skin Care', 35, 1),
('Hair Vitamins', 'Nutritional supplements for healthy hair. Promotes growth and strength.', 850.00, 'post-img-1.jpg', 'Hair Care', 30, 1),
('Beard Roller', 'Microneedle roller for beard growth stimulation. Enhances product absorption.', 950.00, 'post-img-2.jpg', 'Beard Care', 20, 1),
('Eye Cream', 'Reducing dark circles and puffiness. Specially formulated for men.', 580.00, 'left-image.jpg', 'Skin Care', 40, 1),
('Hair Loss Treatment', 'Advanced formula to reduce hair loss. Clinically proven ingredients.', 1250.00, 'related-post-1.jpg', 'Hair Care', 22, 1),
('Beard Care Kit', 'Complete beard care set with oil, balm, and comb. Perfect gift set.', 1450.00, 'service-single.jpg', 'Beard Care', 15, 1),
('Face Mask', 'Detoxifying charcoal face mask. Deep cleans pores and removes impurities.', 480.00, 'post-img-1.jpg', 'Skin Care', 45, 1);

-- Verify the setup
SELECT 'Products table created and populated successfully!' as Status;
SELECT COUNT(*) as Total_Products FROM tblproducts WHERE Status=1;
