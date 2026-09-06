-- Create Products Table for Men Salon Management System
-- Run this SQL in your phpMyAdmin to add product management functionality

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

-- Insert sample products data (16 Products)
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
